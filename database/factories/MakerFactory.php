<?php

namespace Database\Factories;

use App\Models\Maker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Maker>
 */
class MakerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word()
        ];
    }
}
