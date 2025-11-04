<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();
        $type = fake()->randomElement(['full_time','part_time','contract','internship','freelance']);
        $min = fake()->numberBetween(5_000_000, 15_000_000);
        $max = $min + fake()->numberBetween(1_000_000, 15_000_000);
        return [
            'title' => $title,
            'slug' => str($title.' '.fake()->unique()->numberBetween(1, 1_000_000))->slug(),
            'description' => fake()->paragraphs(6, true),
            'employment_type' => $type,
            'experience_min' => fake()->numberBetween(0, 7),
            'experience_max' => fake()->numberBetween(1, 10),
            'salary_min' => $min,
            'salary_max' => $max,
            'salary_currency' => 'IDR',
            'is_remote' => fake()->boolean(30),
            'status' => 'published',
            'valid_until' => now()->addMonths(1),
        ];
    }
}
