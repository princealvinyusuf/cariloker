<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeItem extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_home_items';

    protected $fillable = [
        'section_id',
        'title',
        'subtitle',
        'tag',
        'image_url',
        'icon_url',
        'cta_label',
        'audio_track_id',
        'meta',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_active' => 'boolean',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(HomeSection::class, 'section_id');
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(AudioTrack::class, 'audio_track_id');
    }
}
