<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationEntryArea extends Model
{
    // Composite primary key — no auto-increment id
    public $incrementing = false;
    public $timestamps = false;

    // Point to the first column so Eloquent can build basic queries;
    // composite uniqueness is enforced by the DB primary key constraint.
    protected $primaryKey = 'observation_entry_id';

    protected $fillable = [
        'observation_entry_id',
        'area_code',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function entry(): BelongsTo
    {
        return $this->belongsTo(ObservationEntry::class, 'observation_entry_id');
    }
}
