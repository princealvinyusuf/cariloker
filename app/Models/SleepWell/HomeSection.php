<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HomeSection extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_home_sections';

    protected $fillable = [
        'section_key',
        'title',
        'subtitle',
        'section_type',
        'sort_order',
        'is_active',
        'publish_at',
        'unpublish_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'publish_at' => 'datetime',
        'unpublish_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(HomeItem::class, 'section_id');
    }
}
