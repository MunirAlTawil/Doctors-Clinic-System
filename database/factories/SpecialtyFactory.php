<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialty>
 *
 * For tests or ad-hoc seeding; catalog seeders use explicit names to satisfy unique(name).
 */
class SpecialtyFactory extends Factory
{
    public function definition(): array
    {
        $label = 'Demo Specialty '.fake()->unique()->numerify('####').' '.Str::upper(Str::random(3));

        return [
            'name' => $label,
            'description' => fake()->sentence(12),
            'image_path' => null,
            'is_active' => true,
        ];
    }
}
