<?php

namespace App\Http\Controllers;

use App\Models\ObservationReport;
use App\Models\ObservationReportVenue;
use App\Models\ObservationEntry;
use App\Models\ObservationEntryType;
use App\Models\ObservationEntryArea;
use App\Models\ObservationEntryPhoto;
use App\Models\Ems\FunctionalArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ObservationReportsExport;
use Inertia\Inertia;
use Inertia\Response;

class ObservationController extends Controller
{
    /**
     * Display the observation form.
     * Passes the current user's saved drafts so the UI can offer a resume banner.
     */
    public function create(): Response
    {
        $drafts = ObservationReport::where('user_id', Auth::id())
            ->where('status', 'draft')
            ->with(['venues.entries.types', 'venues.entries.areas'])
            ->latest()
            ->get()
            ->map(fn ($r) => $this->formatDraftForProp($r))
            ->values();

        // Fetch functional areas from database
        $functionalAreas = FunctionalArea::where('active_flag', 1)
            ->orderBy('id')
            ->get()
            ->map(fn ($fa) => [
                'code' => $fa->fa_code,
                'name' => $fa->title,
            ]);

        return Inertia::render('Observation/Form', [
            'drafts' => $drafts,
            'functionalAreas' => $functionalAreas,
        ]);
    }

    /**
     * Store a submitted observation report in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reporter.name'                                    => 'required|string|max:255',
            'reporter.role'                                    => 'nullable|string|max:255',
            'reporter.programme'                               => 'nullable|string|max:255',
            'venues'                                           => 'required|array|min:1',
            'venues.*.type'                                    => 'required|string',
            'venues.*.otherText'                               => 'nullable|string|max:255',
            'venues.*.entries'                                 => 'required|array|min:1',
            'venues.*.entries.*.types'                         => 'required|array',
            'venues.*.entries.*.description'                   => 'required|string|min:40',
            'venues.*.entries.*.areas'                         => 'nullable|array',
            'venues.*.entries.*.areaOther'                     => 'nullable|string|max:255',
            'venues.*.entries.*.applicability'                 => 'required|string|in:must_have,good_to_have,already,na,assess',
            'venues.*.entries.*.photos'                        => 'nullable|array',
            'venues.*.entries.*.photos.*'                      => 'nullable|file|image|max:10240', // 10MB max
        ]);

        $report = DB::transaction(function () use ($validated, $request) {
            // If the user had an open draft, promote it; otherwise create fresh.
            $report = $request->input('draftId')
                ? ObservationReport::find((int) $request->input('draftId'))
                : null;

            if ($report) {
                $this->clearNestedData($report);
            } else {
                $report = new ObservationReport();
            }

            $report->reporter_name      = $validated['reporter']['name'];
            $report->reporter_role      = $validated['reporter']['role'] ?? null;
            $report->reporter_programme = $validated['reporter']['programme'] ?? null;
            $report->user_id            = Auth::id();
            $report->status             = 'submitted';
            $report->submitted_at       = now();
            $report->save();

            $this->persistVenues($report, $validated['venues']);

            return $report;
        });

        $venuesSummary = $report->venues()
            ->with('entries')
            ->get()
            ->values()
            ->map(fn ($v, $i) => [
                'index'      => $i,
                'type'       => $v->venue_type,
                'otherText'  => $v->venue_type_other,
                'entryCount' => $v->entries->count(),
            ])
            ->toArray();

        session([
            'obs_report_id'     => $report->id,
            'obs_reporter_name' => $report->reporter_name,
            'obs_venues'        => $venuesSummary,
            'obs_total_entries' => array_sum(array_column($venuesSummary, 'entryCount')),
        ]);

        return redirect()->route('observation.success');
    }

    /**
     * Upsert a draft — creates on first call, replaces nested data on subsequent calls.
     */
    public function saveDraft(Request $request)
    {
        $request->validate([
            'reporter.name'                    => 'nullable|string|max:255',
            'reporter.role'                    => 'nullable|string|max:255',
            'reporter.programme'               => 'nullable|string|max:255',
            'venues'                           => 'nullable|array',
            'venues.*.type'                    => 'nullable|string|max:255',
            'venues.*.otherText'               => 'nullable|string|max:255',
            'venues.*.entries'                 => 'nullable|array',
            'venues.*.entries.*.description'   => 'nullable|string',
            'venues.*.entries.*.areaOther'     => 'nullable|string|max:255',
            'venues.*.entries.*.applicability' => 'nullable|string|max:50',
        ]);

        $report = DB::transaction(function () use ($request) {
            $draftId = $request->input('draftId');

            $report = $draftId
                ? ObservationReport::where('id', $draftId)->where('status', 'draft')->first()
                : null;

            if ($report) {
                $this->clearNestedData($report);
            } else {
                $report = new ObservationReport(['status' => 'draft', 'user_id' => Auth::id()]);
            }

            $reporter = $request->input('reporter', []);
            $report->reporter_name      = $reporter['name'] ?? null;
            $report->reporter_role      = $reporter['role'] ?? null;
            $report->reporter_programme = $reporter['programme'] ?? null;
            $report->status             = 'draft';
            $report->save();

            $venues = $request->input('venues', []);
            if (!empty($venues)) {
                $this->persistVenues($report, $venues);
            }

            return $report;
        });

        return response()->json([
            'success' => true,
            'draftId' => $report->id,
            'savedAt' => now()->toISOString(),
        ]);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    /**
     * All non-"Other" functional area codes — mirrors FUNCTIONAL_AREAS in observationData.js.
     * Used to expand the "ALL" selection to concrete codes before persisting.
     */
    private const ALL_AREA_CODES = [
        'ACS & ACR','BRO','CAT','CLO','COM','CSR','CUS','DAT','DIS','ENV',
        'EVE','FAN','FIN','GOV','HOS','HR','IT','MAR','MED','RET',
        'SAF','STA','TIC',
    ];

    /**
     * Delete all venues → entries → types/areas for a report so we can
     * re-insert a fresh snapshot (used on re-save of a draft or promotion to submitted).
     */
    private function clearNestedData(ObservationReport $report): void
    {
        foreach ($report->venues as $venue) {
            foreach ($venue->entries as $entry) {
                ObservationEntryType::where('observation_entry_id', $entry->id)->delete();
                ObservationEntryArea::where('observation_entry_id', $entry->id)->delete();
                $entry->photos()->delete();
                $entry->delete();
            }
            $venue->delete();
        }
    }

    /**
     * Persist venues + entries (types, areas) for a report.
     * Expects the raw validated/input array from the form.
     */
    private function persistVenues(ObservationReport $report, array $venues): void
    {
        foreach ($venues as $sortOrder => $venueData) {
            $venue = ObservationReportVenue::create([
                'observation_report_id' => $report->id,
                'venue_type'            => $venueData['type'] ?? null,
                'venue_type_other'      => $venueData['otherText'] ?? null,
                'sort_order'            => $sortOrder,
            ]);

            foreach ($venueData['entries'] ?? [] as $entrySort => $entryData) {
                $entry = ObservationEntry::create([
                    'observation_report_venue_id' => $venue->id,
                    'description'                 => $entryData['description'] ?? '',
                    'area_other'                  => $entryData['areaOther'] ?? null,
                    'applicability'               => $entryData['applicability'] ?: null,
                    'sort_order'                  => $entrySort,
                ]);

                // Types: object keys whose value is truthy
                $typeRows = [];
                foreach ($entryData['types'] ?? [] as $typeId => $checked) {
                    if ($checked) {
                        $typeRows[] = ['observation_entry_id' => $entry->id, 'type' => $typeId];
                    }
                }
                if ($typeRows) {
                    ObservationEntryType::insert($typeRows);
                }

                // Areas: "ALL" expands to every non-Other code; otherwise use truthy keys
                $rawAreas = $entryData['areas'] ?? [];
                if (!empty($rawAreas['ALL'])) {
                    $areaCodes = self::ALL_AREA_CODES;
                } else {
                    $areaCodes = array_keys(array_filter($rawAreas, fn ($v) => $v && $v !== 'ALL'));
                    $areaCodes = array_diff($areaCodes, ['ALL']);
                }
                $areaRows = array_map(
                    fn ($code) => ['observation_entry_id' => $entry->id, 'area_code' => $code],
                    $areaCodes
                );
                if ($areaRows) {
                    ObservationEntryArea::insert(array_values($areaRows));
                }

                // Photos: store uploaded files in private storage
                $photos = $entryData['photos'] ?? [];
                foreach ($photos as $photoSort => $photo) {
                    if ($photo instanceof \Illuminate\Http\UploadedFile) {
                        $path = $photo->store('observation-photos', 'local');
                        
                        ObservationEntryPhoto::create([
                            'observation_entry_id' => $entry->id,
                            'disk'                 => 'local',
                            'path'                 => $path,
                            'original_name'        => $photo->getClientOriginalName(),
                            'mime_type'            => $photo->getMimeType(),
                            'size'                 => $photo->getSize(),
                            'sort_order'           => $photoSort,
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Map an ObservationReport (with eager-loaded venues/entries) to the shape
     * the Form.vue draft banner expects.
     */
    private function formatDraftForProp(ObservationReport $r): array
    {
        return [
            'id'           => $r->id,
            'savedAt'      => $r->updated_at?->toISOString(),
            'totalEntries' => $r->venues->sum(fn ($v) => $v->entries->count()),
            'reporter'     => [
                'name'      => $r->reporter_name ?? '',
                'role'      => $r->reporter_role ?? '',
                'programme' => $r->reporter_programme ?? '',
            ],
            'venues' => $r->venues->values()->map(fn ($v) => [
                'type'      => $v->venue_type ?? '',
                'otherText' => $v->venue_type_other ?? '',
                'entries'   => $v->entries->values()->map(fn ($e) => [
                    'description'   => $e->description ?? '',
                    'types'         => array_fill_keys($e->types->pluck('type')->all(), true),
                    'areas'         => array_fill_keys($e->areas->pluck('area_code')->all(), true),
                    'areaOther'     => $e->area_other ?? '',
                    'applicability' => $e->applicability ?? '',
                    'photos'        => [],
                ])->all(),
            ])->all(),
        ];
    }

    /**
     * Display the admin submissions console
     */
    public function admin(): Response
    {
        // Fetch submitted observation reports from database
        $reports = ObservationReport::where('status', 'submitted')
            ->with([
                'venues.entries.types',
                'venues.entries.areas',
                'venues.entries.photos'
            ])
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Format submissions for the admin page
        $submissions = $reports->map(function ($report) {
            return [
                'id' => 'r' . $report->id,
                'reporter' => [
                    'name' => $report->reporter_name,
                    'role' => $report->reporter_role ?? 'Observer',
                ],
                'submittedAt' => $report->submitted_at ? $report->submitted_at->timestamp * 1000 : now()->timestamp * 1000,
                'venues' => $report->venues->map(function ($venue) {
                    return [
                        'type' => $venue->venue_type_other ?: $venue->venue_type,
                        'entries' => $venue->entries->map(function ($entry) {
                            return [
                                'types' => $entry->types->pluck('type')->toArray(),
                                'areas' => $entry->areas->pluck('area_code')->toArray(),
                                'applicability' => $entry->applicability,
                                'photos' => $entry->photos->map(function ($photo) {
                                    return [
                                        'url' => $photo->url,
                                        'caption' => $photo->original_name,
                                    ];
                                })->toArray(),
                                'description' => $entry->description,
                            ];
                        })->toArray(),
                    ];
                })->toArray(),
            ];
        })->toArray();

        // Fetch functional areas from database
        $functionalAreas = FunctionalArea::where('active_flag', 1)
            ->orderBy('id')
            ->get()
            ->map(fn ($fa) => [
                'code' => $fa->fa_code,
                'name' => $fa->title,
            ]);

        return Inertia::render('Observation/Admin', [
            'submissions' => $submissions,
            'functionalAreas' => $functionalAreas,
        ]);
    }

    /**
     * Delete a draft observation report.
     */
    public function deleteDraft(Request $request, int $draftId)
    {
        $report = ObservationReport::where('id', $draftId)
            ->where('user_id', Auth::id())
            ->where('status', 'draft')
            ->first();

        if (!$report) {
            return response()->json(['error' => 'Draft not found'], 404);
        }

        DB::transaction(function () use ($report) {
            $this->clearNestedData($report);
            $report->delete();
        });

        return response()->json(['success' => true]);
    }

    /**
     * Display the success page after submission
     */
    public function success(Request $request): Response
    {
        return Inertia::render('Observation/Success', [
            'reportId'     => session('obs_report_id'),
            'reporterName' => session('obs_reporter_name'),
            'venues'       => session('obs_venues', []),
            'totalEntries' => session('obs_total_entries', 0),
        ]);
    }

    /**
     * Serve a photo from private storage
     */
    public function servePhoto(ObservationEntryPhoto $photo)
    {
        // Check if the photo exists
        if (!Storage::disk($photo->disk)->exists($photo->path)) {
            abort(404);
        }

        // Return the file
        return response()->file(
            Storage::disk($photo->disk)->path($photo->path),
            [
                'Content-Type' => $photo->mime_type,
                'Content-Disposition' => 'inline; filename="' . $photo->original_name . '"',
            ]
        );
    }

    /**
     * Export observation report to PDF
     */
    public function exportPdf($reportId)
    {
        $report = ObservationReport::where('id', $reportId)
            ->where('status', 'submitted')
            ->with([
                'venues.entries.types',
                'venues.entries.areas',
                'venues.entries.photos'
            ])
            ->firstOrFail();

        // Get functional areas for display names
        $functionalAreas = FunctionalArea::where('active_flag', 1)
            ->get()
            ->keyBy('fa_code');

        // Convert photos to base64 for PDF embedding
        foreach ($report->venues as $venue) {
            foreach ($venue->entries as $entry) {
                foreach ($entry->photos as $photo) {
                    if (Storage::disk($photo->disk)->exists($photo->path)) {
                        $imageData = Storage::disk($photo->disk)->get($photo->path);
                        $photo->base64 = 'data:' . $photo->mime_type . ';base64,' . base64_encode($imageData);
                    }
                }
            }
        }

        $data = [
            'report' => $report,
            'functionalAreas' => $functionalAreas,
        ];

        $pdf = Pdf::loadView('observation.pdf-report', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 5)
            ->setOption('margin-right', 5)
            ->setOption('margin-bottom', 5)
            ->setOption('margin-left', 5);

        $filename = 'observation-report-' . $report->id . '-' . date('Y-m-d') . '.pdf';

        return $pdf->stream($filename);
    }

    /**
     * Export all observation reports to Excel
     */
    public function exportExcel()
    {
        $filename = 'observation-reports-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new ObservationReportsExport, $filename);
    }
}
