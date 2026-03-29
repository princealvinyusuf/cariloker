<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrackedNight extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_tracked_nights';

    protected $fillable = [
        'listener_id',
        'session_id',
        'preferred_track_id',
        'started_at',
        'ended_at',
        'tracked_date',
        'entry_point',
        'status',
        'sleep_goal_minutes',
        'smart_alarm_window_minutes',
        'wake_alarm_time',
        'recording_path',
        'recording_duration_seconds',
        'recording_uploaded_at',
        'last_analyzed_at',
        'mix_snapshot',
        'metadata',
        'insights_payload',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'tracked_date' => 'date',
        'recording_uploaded_at' => 'datetime',
        'last_analyzed_at' => 'datetime',
        'mix_snapshot' => 'array',
        'metadata' => 'array',
        'insights_payload' => 'array',
    ];

    public function listener(): BelongsTo
    {
        return $this->belongsTo(Listener::class, 'listener_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(SleepSession::class, 'session_id');
    }

    public function preferredTrack(): BelongsTo
    {
        return $this->belongsTo(AudioTrack::class, 'preferred_track_id');
    }

    public function detections(): HasMany
    {
        return $this->hasMany(TrackedNightDetection::class, 'tracked_night_id');
    }

    public function recordings(): HasMany
    {
        return $this->hasMany(TrackedNightRecording::class, 'tracked_night_id');
    }
}
