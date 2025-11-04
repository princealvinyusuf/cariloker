<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company();
        return [
            'name' => $name,
            'slug' => str($name)->slug(),
            'website_url' => fake()->url(),
            'industry' => fake()->randomElement(['Technology','Finance','Retail','Healthcare','Education']),
            'size' => fake()->randomElement(['1-10','11-50','51-200','201-500','500+']),
            'founded_year' => fake()->numberBetween(1990, 2024),
            'description' => fake()->paragraphs(2, true),
        ];
    }
}
