<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SleepSession extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_sleep_sessions';

    protected $fillable = [
        'listener_id',
        'started_at',
        'ended_at',
        'duration_seconds',
        'mode',
        'entry_point',
        'device_local_date',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    public function listener(): BelongsTo
    {
        return $this->belongsTo(Listener::class, 'listener_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(SessionEvent::class, 'session_id');
    }
}
