<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobCategory>
 */
class JobCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Design', 'Engineering', 'Marketing', 'Sales', 'Product', 'Operations', 'Finance', 'HR', 'Support',
        ]);
        return [
            'name' => $name,
            'slug' => str($name.'-'.fake()->unique()->numberBetween(1, 1000000))->slug(),
        ];
    }
}
