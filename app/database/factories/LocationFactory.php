<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = [
            ['city' => 'Jakarta', 'state' => 'DKI Jakarta', 'country' => 'ID'],
            ['city' => 'Bandung', 'state' => 'Jawa Barat', 'country' => 'ID'],
            ['city' => 'Surabaya', 'state' => 'Jawa Timur', 'country' => 'ID'],
            ['city' => 'Yogyakarta', 'state' => 'DI Yogyakarta', 'country' => 'ID'],
            ['city' => 'Semarang', 'state' => 'Jawa Tengah', 'country' => 'ID'],
            ['city' => 'Medan', 'state' => 'Sumatera Utara', 'country' => 'ID'],
            ['city' => 'Denpasar', 'state' => 'Bali', 'country' => 'ID'],
        ];
        $city = fake()->randomElement($cities);
        return [
            'city' => $city['city'],
            'state' => $city['state'],
            'country' => $city['country'],
            'latitude' => fake()->latitude(-10, 6),
            'longitude' => fake()->longitude(95, 141),
        ];
    }
}
