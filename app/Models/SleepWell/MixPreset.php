<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MixPreset extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_mix_presets';

    protected $fillable = [
        'listener_id',
        'name',
        'channels',
    ];

    protected $casts = [
        'channels' => 'array',
    ];

    public function listener(): BelongsTo
    {
        return $this->belongsTo(Listener::class, 'listener_id');
    }
}
