<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObservationEntry extends Model
{
    protected $fillable = [
        'observation_report_venue_id',
        'description',
        'area_other',
        'applicability',
        'sort_order',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function venue(): BelongsTo
    {
        return $this->belongsTo(ObservationReportVenue::class, 'observation_report_venue_id');
    }

    public function types(): HasMany
    {
        return $this->hasMany(ObservationEntryType::class, 'observation_entry_id');
    }

    public function areas(): HasMany
    {
        return $this->hasMany(ObservationEntryArea::class, 'observation_entry_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ObservationEntryPhoto::class, 'observation_entry_id')
                    ->orderBy('sort_order');
    }

    // ── Convenience accessors ────────────────────────────────────────────────

    /** e.g. ['observation', 'best_practice'] */
    public function getTypeListAttribute(): array
    {
        return $this->types->pluck('type')->all();
    }

    /** e.g. ['SAF', 'TIC'] */
    public function getAreaCodeListAttribute(): array
    {
        return $this->areas->pluck('area_code')->all();
    }
}
