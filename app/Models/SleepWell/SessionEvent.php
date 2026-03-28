<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionEvent extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_session_events';

    protected $fillable = [
        'session_id',
        'track_id',
        'event_type',
        'event_at',
        'position_seconds',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'event_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(SleepSession::class, 'session_id');
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(AudioTrack::class, 'track_id');
    }
}
