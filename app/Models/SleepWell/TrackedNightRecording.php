<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackedNightRecording extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_tracked_night_recordings';

    protected $fillable = [
        'tracked_night_id',
        'label',
        'detection_key',
        'start_second',
        'duration_seconds',
        'confidence_score',
        'source_path',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function trackedNight(): BelongsTo
    {
        return $this->belongsTo(TrackedNight::class, 'tracked_night_id');
    }
}
