<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarImage;
use App\Models\CarType;
use App\Models\City;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\Model;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Car Types
        CarType::factory()->sequence(
            ['name' => 'Sedan'],
            ['name' => 'Hatchback'],
            ['name' => 'SUV'],
            ['name' => 'Pickup Truck'],
            ['name' => 'Minivan'],
            ['name' => 'Jeep'],
            ['name' => 'Coupe'],
            ['name' => 'Crossover'],
            ['name' => 'Sports Car']
        )->count(9)->create();

        // 2. Seed Fuel Types
        FuelType::factory()->sequence(
            ['name' => 'Gasoline'],
            ['name' => 'Diesel'],
            ['name' => 'Electric'],
            ['name' => 'Hybrid']
        )->count(4)->create();

        // 3. Expanded States & Cities Data
        $states = [
            'California' => ['Los Angeles', 'San Francisco', 'San Diego'],
            'Texas'      => ['Houston', 'Austin', 'Dallas'],
            'New York'   => ['New York City', 'Buffalo', 'Albany'],
            'Florida'    => ['Miami', 'Orlando', 'Tampa', 'Jacksonville'],
            'Ohio'       => ['Columbus', 'Cleveland', 'Cincinnati', 'Toledo'],
            'Washington' => ['Seattle', 'Spokane', 'Tacoma', 'Olympia'],
        ];

        foreach ($states as $stateName => $cities) {
            State::factory()
                ->state(['name' => $stateName])
                ->has(
                    City::factory()
                        ->count(count($cities))
                        ->sequence(...array_map(fn($city) => ['name' => $city], $cities))
                )
                ->create();
        }

        // 4. Expanded Car Makers & 6 Models Each
        $makers = [
            'Toyota'    => ['Camry', 'Corolla', 'RAV4', 'Prius', 'Highlander', 'Tacoma'],
            'Ford'      => ['F-150', 'Mustang', 'Explorer', 'Escape', 'Bronco', 'Ranger'],
            'Tesla'     => ['Model S', 'Model 3', 'Model X', 'Model Y', 'Cybertruck', 'Roadster'],
            'Honda'     => ['Civic', 'Accord', 'CR-V', 'Pilot', 'Odyssey', 'Prelude'],
            'Chevrolet' => ['Silverado', 'Equinox', 'Malibu', 'Tahoe', 'Corvette', 'Colorado'],
            'Nissan'    => ['Altima', 'Sentra', 'Rogue', 'Pathfinder', 'Frontier', 'Leaf'],
            'Lexus'     => ['RX', 'NX', 'ES', 'GX', 'IS', 'LX'],
        ];

        foreach ($makers as $makerName => $models) {
            Maker::factory()
                ->state(['name' => $makerName])
                ->has(
                    Model::factory()
                        ->count(count($models))
                        ->sequence(...array_map(fn($model) => ['name' => $model], $models))
                )
                ->create();
        }

        // 5. Seed 3 Standalone Users
        User::factory()->count(3)->create();

        // 6. Seed 2 Users, each with 50 favourite cars, each car having 5 images
        User::factory()
            ->count(2)
            ->has(
                Car::factory()
                    ->count(50)
                    ->has(
                        CarImage::factory()
                            ->count(5)
                            ->sequence(fn (Sequence $sequence) => ['position' => $sequence->index % 5 + 1]),
                        'images' // <-- Moved safely out of the sequence() method call!
                    )
                    ->hasFeatures(), 
                'favouriteCars' 
            )
            ->create();
    }
}