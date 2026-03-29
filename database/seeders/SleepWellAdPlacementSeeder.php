<?php

namespace Database\Seeders;

use App\Models\SleepWell\AdPlacement;
use Illuminate\Database\Seeder;

class SleepWellAdPlacementSeeder extends Seeder
{
    public function run(): void
    {
        $placements = [
            ['screen' => 'home', 'slot_key' => 'feed_banner', 'format' => 'banner', 'enabled' => true, 'priority' => 100],
            ['screen' => 'profile', 'slot_key' => 'mid_banner', 'format' => 'banner', 'enabled' => true, 'priority' => 80],
            ['screen' => 'settings', 'slot_key' => 'list_banner', 'format' => 'banner', 'enabled' => true, 'priority' => 70],
        ];

        foreach ($placements as $placement) {
            AdPlacement::query()->updateOrCreate(
                ['screen' => $placement['screen'], 'slot_key' => $placement['slot_key']],
                [
                    'format' => $placement['format'],
                    'enabled' => $placement['enabled'],
                    'frequency_cap' => 0,
                    'countries' => null,
                    'priority' => $placement['priority'],
                ]
            );
        }
    }
}
