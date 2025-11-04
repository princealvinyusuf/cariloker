<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Figma', 'UX Research', 'UI Design', 'Laravel', 'PHP', 'JavaScript', 'React', 'Vue', 'SQL', 'AWS', 'Docker', 'Kubernetes'
        ]);
        return [
            'name' => $name,
            'slug' => str($name.'-'.fake()->unique()->numberBetween(1, 1000000))->slug(),
        ];
    }
}
