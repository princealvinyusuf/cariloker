<?php

namespace App\Models\SleepWell;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Listener extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_listeners';

    protected $fillable = [
        'user_id',
        'device_id',
        'timezone',
        'sleep_difficulty',
        'prefers_talking',
        'preferred_categories',
        'preferred_sound_types',
        'last_active_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'prefers_talking' => 'boolean',
        'preferred_categories' => 'array',
        'preferred_sound_types' => 'array',
        'last_active_at' => 'datetime',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(SleepSession::class, 'listener_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
