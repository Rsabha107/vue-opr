<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ObservationReport extends Model
{
    protected $fillable = [
        'user_id',
        'reporter_name',
        'reporter_role',
        'reporter_programme',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function venues(): HasMany
    {
        return $this->hasMany(ObservationReportVenue::class)
                    ->orderBy('sort_order');
    }

    /** All entries across every venue belonging to this report. */
    public function entries(): HasManyThrough
    {
        return $this->hasManyThrough(
            ObservationEntry::class,
            ObservationReportVenue::class,
            'observation_report_id',      // FK on observation_report_venues
            'observation_report_venue_id' // FK on observation_entries
        );
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }
}
