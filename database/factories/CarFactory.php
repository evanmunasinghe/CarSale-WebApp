<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

/**
 * @extends Factory<Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'maker_id' => Maker::inRandomOrder()->first()->id,
        'model_id' => function (array $attributes) {
            return Model::where('maker_id', $attributes['maker_id'])->inRandomOrder()->first()->id;
        },
        'year' => fake()->year(),
        'price' => ((int)fake()->randomFloat(2, 5, 100)) * 1000,
        'vin' => strtoupper(Str::random(17)),
        'mileage' => ((int)fake()->randomFloat(2, 5, 100)) * 1000,
        'car_type_id' => CarType::inRandomOrder()->first()->id,
        'fuel_type_id' => FuelType::inRandomOrder()->first()->id,
        'user_id' => User::inRandomOrder()->first()->id,
        'city_id' => City::inRandomOrder()->first()->id,
        'address' => fake()->address(),
        'phone' => function (array $attributes) {
            return User::find($attributes['user_id'])->phone;
        },
        'description' => fake()->text(1000),
        'published_at' => fake()->optional(0.9)->dateTimeBetween('-1 month', '+1 day'),
        ];
    }
}
