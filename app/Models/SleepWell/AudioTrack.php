<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioTrack extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_audio_tracks';

    protected $fillable = [
        'title',
        'category',
        'sound_type',
        'duration_seconds',
        'talking',
        'is_active',
        'stream_url',
        'cover_image_url',
    ];

    protected $casts = [
        'talking' => 'boolean',
        'is_active' => 'boolean',
    ];
}
