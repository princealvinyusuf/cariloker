<?php

namespace App\Models\SleepWell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdPlacement extends Model
{
    use HasFactory;

    protected $table = 'sleepwell_ad_placements';

    protected $fillable = [
        'screen',
        'slot_key',
        'format',
        'enabled',
        'frequency_cap',
        'countries',
        'priority',
        'ad_unit_id_android',
        'ad_unit_id_ios',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'frequency_cap' => 'integer',
        'priority' => 'integer',
    ];
}
