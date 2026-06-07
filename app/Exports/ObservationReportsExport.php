<?php

namespace App\Exports;

use App\Models\ObservationReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ObservationReportsExport implements FromCollection, WithHeadings, WithMapping
{
    // Maps entry type IDs to their labels
    protected const ENTRY_TYPE_LABELS = [
        'observation' => 'Observation',
        'best_practice' => 'Best Practice',
        'innovation' => 'Innovation',
        'challenge' => 'Challenge',
        'recommendation' => 'Recommendation',
    ];

    // Maps applicability IDs to their labels
    protected const APPLICABILITY_LABELS = [
        'must_have' => 'Must Have',
        'good_to_have' => 'Good to Have',
        'already' => 'Already Implemented in-Venue',
        'na' => 'Not Applicable',
        'assess' => 'Requires Further Assessment',
    ];

    public function collection()
    {
        return ObservationReport::with([
            'user',
            'venues.entries.types',
            'venues.entries.areas',
            'venues.entries.photos'
        ])
        ->where('status', 'submitted')
        ->orderBy('submitted_at', 'desc')
        ->get();
    }

    public function headings(): array
    {
        return [
            'Report ID',
            'Reporter Name',
            'Reporter Role',
            'Reporter Programme',
            'Submitted At',
            'Venues Count',
            'Entries Count',
            'Photos Count',
            'Venue Types',
            'Entry Types',
            'Functional Areas',
            'Applicability',
            'Descriptions'
        ];
    }

    public function map($report): array
    {
        $venues = $report->venues;
        $entries = $venues->flatMap->entries;
        
        // Aggregate data
        $venueTypes = $venues->pluck('venue_type')->filter()->unique()->values()->implode(', ');
        
        // Map entry type IDs to labels
        $entryTypeIds = $entries->flatMap->types->pluck('type')->unique()->values();
        $entryTypes = $entryTypeIds->map(fn($id) => self::ENTRY_TYPE_LABELS[$id] ?? $id)->implode(', ');
        
        $functionalAreas = $entries->flatMap->areas->pluck('area_code')->unique()->values()->implode(', ');
        
        // Map applicability IDs to labels
        $applicabilityIds = $entries->pluck('applicability')->filter()->unique()->values();
        $applicabilities = $applicabilityIds->map(fn($id) => self::APPLICABILITY_LABELS[$id] ?? $id)->implode(', ');
        
        $descriptions = $entries->pluck('description')->implode(' | ');
        
        return [
            $report->id,
            $report->reporter_name,
            $report->reporter_role,
            $report->reporter_programme,
            $report->submitted_at?->format('Y-m-d H:i:s'),
            $venues->count(),
            $entries->count(),
            $entries->sum(fn($e) => $e->photos->count()),
            $venueTypes,
            $entryTypes,
            $functionalAreas,
            $applicabilities,
            $descriptions
        ];
    }
}
