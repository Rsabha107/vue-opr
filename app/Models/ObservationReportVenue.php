<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObservationReportVenue extends Model
{
    protected $fillable = [
        'observation_report_id',
        'venue_id',
        'venue_type',
        'venue_type_other',
        'sort_order',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function report(): BelongsTo
    {
        return $this->belongsTo(ObservationReport::class, 'observation_report_id');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(ObservationEntry::class, 'observation_report_venue_id')
                    ->orderBy('sort_order');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /** Resolves "Other (please specify)" to the free-text value, otherwise the type. */
    public function getDisplayNameAttribute(): string
    {
        return $this->venue_type_other ?: ($this->venue_type ?: 'Unnamed Venue');
    }
}
