<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ObservationEntryPhoto extends Model
{
    protected $fillable = [
        'observation_entry_id',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
        'sort_order',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function entry(): BelongsTo
    {
        return $this->belongsTo(ObservationEntry::class, 'observation_entry_id');
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    /** URL for accessing the photo (via secure route for private storage). */
    public function getUrlAttribute(): string
    {
        return route('observation.photo', ['photo' => $this->id]);
    }
}
