<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingResponse extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_onboarding_responses';

    protected $fillable = [
        'listener_id',
        'answers',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'completed_at' => 'datetime',
    ];

    public function listener(): BelongsTo
    {
        return $this->belongsTo(Listener::class, 'listener_id');
    }
}
