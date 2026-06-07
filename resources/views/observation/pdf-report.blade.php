<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Observation Report - {{ $report->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5pt;
            line-height: 1.5;
            color: #1e293b;
            background: #ffffff;
        }

        /* ── Header ── */
        .header {
            background: #0f172a;
            color: #ffffff;
            padding: 16px 20px 14px;
            border-bottom: 4px solid #3b82f6;
        }

        .header-row {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 100px;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: 700;
            letter-spacing: 0.4px;
            color: #ffffff;
            margin-bottom: 2px;
        }

        .header .subtitle {
            font-size: 8.5pt;
            color: #94a3b8;
            letter-spacing: 0.3px;
        }

        .report-id-chip {
            background: #3b82f6;
            color: #ffffff;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 9pt;
            font-weight: 700;
        }

        /* ── Meta Summary ── */
        .summary-bar {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-top: none;
            padding: 10px 16px;
            margin-bottom: 14px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            padding: 3px 10px 3px 0;
            vertical-align: top;
        }

        .summary-table .lbl {
            font-weight: 700;
            color: #64748b;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
            padding-right: 5px;
        }

        .summary-table .val {
            color: #1e293b;
            font-size: 9.5pt;
        }

        .summary-table .sep {
            border-left: 1px solid #e2e8f0;
            padding-left: 12px;
        }

        /* ── Venue Section ── */
        .venue-section {
            margin-bottom: 14px;
        }

        .venue-header {
            background: #1e293b;
            color: #ffffff;
            padding: 8px 14px;
            font-size: 10pt;
            font-weight: 700;
            border-left: 5px solid #3b82f6;
        }

        .venue-num {
            color: #93c5fd;
            margin-right: 6px;
        }

        /* ── Entry Card ── */
        .entry-card {
            border: 1px solid #e2e8f0;
            border-top: none;
            border-left: 5px solid #cbd5e1;
            padding: 10px 12px;
            margin-bottom: 2px;
            page-break-inside: avoid;
            background: #ffffff;
        }

        .entry-card.t-observation    { border-left-color: #3b82f6; }
        .entry-card.t-best_practice  { border-left-color: #22c55e; }
        .entry-card.t-innovation     { border-left-color: #f59e0b; }
        .entry-card.t-challenge      { border-left-color: #ef4444; }
        .entry-card.t-recommendation { border-left-color: #a855f7; }

        .entry-number {
            font-size: 7.5pt;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .entry-badges {
            margin-bottom: 6px;
        }

        .type-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: 700;
            margin-right: 4px;
            margin-bottom: 3px;
        }

        .type-observation    { background: #dbeafe; color: #1d4ed8; }
        .type-best_practice  { background: #dcfce7; color: #15803d; }
        .type-innovation     { background: #fef9c3; color: #a16207; }
        .type-challenge      { background: #fee2e2; color: #b91c1c; }
        .type-recommendation { background: #f3e8ff; color: #7e22ce; }

        .appl-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: 600;
            border: 1px solid;
            margin-bottom: 3px;
        }

        .appl-must_have    { color: #991b1b; border-color: #fca5a5; background: #fff5f5; }
        .appl-good_to_have { color: #166534; border-color: #86efac; background: #f0fdf4; }
        .appl-already      { color: #1e40af; border-color: #93c5fd; background: #eff6ff; }
        .appl-na           { color: #6b7280; border-color: #d1d5db; background: #f9fafb; }
        .appl-assess       { color: #92400e; border-color: #fcd34d; background: #fffbeb; }

        .description {
            font-size: 9.5pt;
            line-height: 1.65;
            color: #334155;
            padding: 7px 0;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            margin: 5px 0;
        }

        /* ── Functional Areas ── */
        .functional-areas {
            margin-top: 7px;
        }

        .fa-label {
            display: block;
            font-weight: 700;
            font-size: 7.5pt;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .fa-pill {
            display: inline-block;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 8pt;
            margin-right: 4px;
            margin-bottom: 3px;
            color: #475569;
        }

        .fa-code {
            font-weight: 700;
            color: #1e293b;
        }

        /* ── Photo Attachments ── */
        .section-title {
            background: #1e293b;
            color: #ffffff;
            padding: 8px 14px;
            font-size: 10pt;
            font-weight: 700;
            border-left: 5px solid #3b82f6;
            margin-bottom: 0;
        }

        .photo-entry-card {
            border: 1px solid #e2e8f0;
            border-top: none;
            padding: 12px;
            margin-bottom: 6px;
            background: #ffffff;
            page-break-inside: avoid;
        }

        .photo-entry-label {
            display: block;
            font-weight: 700;
            color: #1e293b;
            font-size: 9pt;
            margin-bottom: 2px;
        }

        .photo-entry-desc {
            font-size: 8.5pt;
            color: #64748b;
            margin-bottom: 10px;
        }

        .photo-caption {
            padding: 4px 8px;
            font-size: 7.5pt;
            color: #6b7280;
            background: #f1f5f9;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 20px;
            padding-top: 8px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            letter-spacing: 0.3px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <div class="header-row">
            <div class="header-left">
                <h1>OBSERVATION PROGRAMME REPORT</h1>
                <div class="subtitle">End-of-Assignment Report</div>
            </div>
            <div class="header-right">
                <span class="report-id-chip">#{{ $report->id }}</span>
            </div>
        </div>
    </div>

    <!-- Summary Bar -->
    <div class="summary-bar">
        <table class="summary-table">
            <tr>
                <td class="lbl">Reporter</td>
                <td class="val">{{ $report->reporter_name }}</td>
            </tr>
            @if($report->reporter_role)
            <tr>
                <td class="lbl">Role</td>
                <td class="val">{{ $report->reporter_role }}</td>
            </tr>
            @endif
            @if($report->reporter_programme)
            <tr>
                <td class="lbl">Programme</td>
                <td class="val">{{ $report->reporter_programme }}</td>
            </tr>
            @endif
            <tr>
                <td class="lbl">Submitted</td>
                <td class="val">{{ $report->submitted_at->format('d F Y, H:i') }}</td>
                <td class="lbl sep">Venues</td>
                <td class="val">{{ $report->venues->count() }}</td>
                <td class="lbl sep">Entries</td>
                <td class="val">{{ $report->venues->sum(fn($v) => $v->entries->count()) }}</td>
            </tr>
        </table>
    </div>

    <!-- Venues & Entries -->
    @foreach($report->venues as $venueIndex => $venue)
        <div class="venue-section">
            <div class="venue-header">
                <span class="venue-num">{{ str_pad($venueIndex + 1, 2, '0', STR_PAD_LEFT) }}</span>
                {{ $venue->venue_type_other ?: $venue->venue_type }}
            </div>

            @foreach($venue->entries as $entryIndex => $entry)
                @php
                    $primaryType = $entry->types->first()?->type ?? 'observation';
                    $typeLabels = [
                        'observation'    => 'Observation',
                        'best_practice'  => 'Best Practice',
                        'innovation'     => 'Innovation',
                        'challenge'      => 'Challenge',
                        'recommendation' => 'Recommendation',
                    ];
                    $applLabels = [
                        'must_have'    => 'Must Have',
                        'good_to_have' => 'Good to Have',
                        'already'      => 'Already Implemented',
                        'na'           => 'Not Applicable',
                        'assess'       => 'Requires Assessment',
                    ];
                @endphp
                <div class="entry-card t-{{ $primaryType }}">

                    <div class="entry-number">Entry {{ $entryIndex + 1 }}</div>

                    <div class="entry-badges">
                        @foreach($entry->types as $type)
                            <span class="type-badge type-{{ $type->type }}">
                                {{ $typeLabels[$type->type] ?? $type->type }}
                            </span>
                        @endforeach

                        @if($entry->applicability)
                            <span class="appl-badge appl-{{ $entry->applicability }}">
                                {{ $applLabels[$entry->applicability] ?? $entry->applicability }}
                            </span>
                        @endif
                    </div>

                    <div class="description">{{ $entry->description }}</div>

                    @if($entry->areas->count() > 0)
                        <div class="functional-areas">
                            <span class="fa-label">Functional Areas Impact</span>
                            @foreach($entry->areas as $area)
                                <span class="fa-pill">
                                    <span class="fa-code">{{ $area->area_code }}</span>
                                    @if(isset($functionalAreas[$area->area_code]))
                                        &mdash; {{ $functionalAreas[$area->area_code]->title }}
                                    @endif
                                </span>
                            @endforeach
                            @if($entry->area_other)
                                <div style="margin-top: 5px; font-size: 8.5pt; color: #475569;">
                                    <strong>Other:</strong> {{ $entry->area_other }}
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($entry->photos->count() > 0)
                        <div style="margin-top: 7px; padding-top: 6px; border-top: 1px solid #f1f5f9; font-size: 8pt; color: #64748b; font-weight: 700;">
                            {{ $entry->photos->count() }} photo(s) attached &mdash; see Photo Attachments section
                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    @endforeach

    <!-- Photo Attachments -->
    @php
        $hasPhotos = $report->venues->some(fn($v) => $v->entries->some(fn($e) => $e->photos->count() > 0));
    @endphp

    @if($hasPhotos)
        <div class="page-break"></div>

        <div class="section-title">PHOTO ATTACHMENTS</div>

        @foreach($report->venues as $venueIndex => $venue)
            @foreach($venue->entries as $entryIndex => $entry)
                @if($entry->photos->count() > 0)
                    <div class="photo-entry-card">
                        <span class="photo-entry-label">
                            Venue {{ $venueIndex + 1 }} &mdash; Entry {{ $entryIndex + 1 }}
                        </span>
                        <div class="photo-entry-desc">{{ Str::limit($entry->description, 120) }}</div>

                        @php $photoList = $entry->photos->values(); @endphp
                        <table width="100%" cellpadding="4" cellspacing="0" border="0">
                            @for ($pi = 0; $pi < $photoList->count(); $pi += 2)
                                <tr>
                                    <td width="50%" style="vertical-align: top; padding: 4px;">
                                        @php $p1 = $photoList[$pi]; @endphp
                                        <div style="border: 1px solid #e2e8f0; border-radius: 3px; overflow: hidden; background: #f8fafc;">
                                            @if(isset($p1->base64))
                                                <img src="{{ $p1->base64 }}" alt="{{ $p1->original_name }}" style="width: 100%; height: auto; display: block;" />
                                            @else
                                                <div style="height: 120px; background: #f1f5f9; text-align: center; padding-top: 48px; color: #94a3b8; font-size: 8pt;">[Photo unavailable]</div>
                                            @endif
                                            <div class="photo-caption">{{ $p1->original_name }}</div>
                                        </div>
                                    </td>
                                    @if ($pi + 1 < $photoList->count())
                                        @php $p2 = $photoList[$pi + 1]; @endphp
                                        <td width="50%" style="vertical-align: top; padding: 4px;">
                                            <div style="border: 1px solid #e2e8f0; border-radius: 3px; overflow: hidden; background: #f8fafc;">
                                                @if(isset($p2->base64))
                                                    <img src="{{ $p2->base64 }}" alt="{{ $p2->original_name }}" style="width: 100%; height: auto; display: block;" />
                                                @else
                                                    <div style="height: 120px; background: #f1f5f9; text-align: center; padding-top: 48px; color: #94a3b8; font-size: 8pt;">[Photo unavailable]</div>
                                                @endif
                                                <div class="photo-caption">{{ $p2->original_name }}</div>
                                            </div>
                                        </td>
                                    @else
                                        <td width="50%"></td>
                                    @endif
                                </tr>
                            @endfor
                        </table>
                    </div>
                @endif
            @endforeach
        @endforeach
    @endif

    <div class="footer">
        Generated on {{ now()->format('d F Y, H:i') }} &nbsp;&bull;&nbsp; Observation Programme Reporting System
    </div>

</body>
</html>
