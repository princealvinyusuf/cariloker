<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackedNightDetection extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_tracked_night_detections';

    protected $fillable = [
        'tracked_night_id',
        'detection_key',
        'label',
        'occurrence_count',
        'total_seconds',
        'confidence_score',
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
