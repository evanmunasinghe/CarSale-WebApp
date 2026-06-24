<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Maker;
use App\Models\CarType;
use App\Models\FuelType;
use App\Models\State;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cars = $request->user()
            ->cars()
            ->with(['primaryImage', 'maker', 'model'])
            ->orderBy("created_at", "desc")
            ->paginate(15);
        return view('car.index', ['cars' => $cars]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $makers = Maker::with('models')->orderBy('name', 'asc')->get();
        $carTypes = CarType::orderBy('name', 'asc')->get();
        $fuelTypes = FuelType::orderBy('name', 'asc')->get();
        $states = State::with('cities')->orderBy('name', 'asc')->get();

        return view('car.create', compact('makers', 'carTypes', 'fuelTypes', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        foreach ($request->file('images', []) as $image) {
            if (!$image->isValid()) {
                return back()
                    ->withErrors(['images' => 'Image upload failed: ' . $image->getErrorMessage()])
                    ->withInput();
            }
        }

   
        $data = $request->validate([
            'maker_id' => ['required', 'exists:makers,id'],
            'model_id' => ['required', 'exists:models,id'],
            'year' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'vin' => ['required', 'string', 'max:255'],
            'mileage' => ['nullable', 'integer'],
            'car_type_id' => ['required', 'exists:car_types,id'],
            'fuel_type_id' => ['required', 'exists:fuel_types,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:45'],
            'description' => ['nullable', 'string'],
            'published' => ['nullable'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);

        $data['user_id'] = $request->user()->id;
        $data['published_at'] = $request->has('published') ? now() : null;

        $car = Car::create($data);

        $car->features()->create([
            'abs' => $request->has('abs'),
            'air_conditioning' => $request->has('air_conditioning'),
            'power_windows' => $request->has('power_windows'),
            'power_door_locks' => $request->has('power_door_locks'),
            'cruise_control' => $request->has('cruise_control'),
            'bluetooth_connectivity' => $request->has('bluetooth_connectivity'),
            'remote_start' => $request->has('remote_start'),
            'gps_navigation' => $request->has('gps_navigation'),
            'heater_seats' => $request->has('heater_seats'),
            'climate_control' => $request->has('climate_control'),
            'rear_parking_sensors' => $request->has('rear_parking_sensors'),
            'leather_seats' => $request->has('leather_seats'),
        ]);

        foreach ($request->file('images', []) as $index => $image) {
            if ($image->isValid()) {
                $path = $image->store('car-images', 'public');

                $car->images()->create([
                    'image_path' => $path,
                    'position' => $index + 1,
                ]);
            }
        }
        

        return redirect()->route('car.index')->with('success', 'Vehicle listing created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Car $car)
    {
        return view('car.show', ['car' => $car]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        abort_unless($car->user_id === request()->user()->id, 403);

        // 1. Fetch dropdown options for form populations
        $makers = Maker::with('models')->orderBy('name', 'asc')->get();
        $carTypes = CarType::orderBy('name', 'asc')->get();
        $fuelTypes = FuelType::orderBy('name', 'asc')->get();
        $states = State::with('cities')->orderBy('name', 'asc')->get();

        // 2. Eager load specific bindings on this target car
        $car->load(['features', 'images']);

        // 3. Resolve the active state ID context from the current car city properties
        $currentStateId = $car->city ? $car->city->state_id : null;

        // 4. Return layout bundle down to the edit blade file
        return view('car.edit', compact('car', 'makers', 'carTypes', 'fuelTypes', 'states', 'currentStateId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Car $car)
    {
        abort_unless($car->user_id === $request->user()->id, 403);

        foreach ($request->file('images', []) as $image) {
            if (!$image->isValid()) {
                return back()
                    ->withErrors(['images' => 'Image upload failed: ' . $image->getErrorMessage()])
                    ->withInput();
            }
        }

        // 1. Validate the updated payload structure
        $data = $request->validate([
            'maker_id' => ['required', 'exists:makers,id'],
            'model_id' => ['required', 'exists:models,id'],
            'year' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'vin' => ['required', 'string', 'max:255'],
            'mileage' => ['nullable', 'integer'],
            'car_type_id' => ['required', 'exists:car_types,id'],
            'fuel_type_id' => ['required', 'exists:fuel_types,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:45'],
            'description' => ['nullable', 'string'],
            'published' => ['nullable'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:5120'],
        ]);

        // 2. Compute published timestamps safely
        $data['published_at'] = $request->has('published') ? ($car->published_at ?? now()) : null;

        // 3. Sync fields on parent model
        $car->update($data);

        // 4. Overwrite features check matrix natively using updateOrCreate
        $car->features()->updateOrCreate([], [
            'abs' => $request->has('abs'),
            'air_conditioning' => $request->has('air_conditioning'),
            'power_windows' => $request->has('power_windows'),
            'power_door_locks' => $request->has('power_door_locks'),
            'cruise_control' => $request->has('cruise_control'),
            'bluetooth_connectivity' => $request->has('bluetooth_connectivity'),
            'remote_start' => $request->has('remote_start'),
            'gps_navigation' => $request->has('gps_navigation'),
            'heater_seats' => $request->has('heater_seats'),
            'climate_control' => $request->has('climate_control'),
            'rear_parking_sensors' => $request->has('rear_parking_sensors'),
            'leather_seats' => $request->has('leather_seats'),
        ]);

        // 5. Append additional multi-file images to disk
        $currentMaxPos = $car->images()->max('position') ?? 0;

        foreach ($request->file('images', []) as $image) {
            if ($image->isValid()) {
                $path = $image->store('car-images', 'public');
                $currentMaxPos++;

                $car->images()->create([
                    'image_path' => $path,
                    'position' => $currentMaxPos,
                ]);
            }
        }

        return redirect()->route('car.index')->with('success', 'Vehicle data updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Car $car)
    {
        abort_unless($car->user_id === request()->user()->id, 403);

        $car->delete();

        return redirect()->route('car.index')->with('success', 'Vehicle listing has been removed successfully.');
    }

    public function search()
    {
        $query = Car::where('published_at', '<=', now())
            ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model'])
            ->orderBy('published_at', 'desc');

        $cars = $query->paginate(15);

        return view('car.search', ['cars' => $cars]);
    }

    public function watchlist(Request $request)
    {
        $cars = $request->user()->favouriteCars()
            ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model'])
            ->paginate(15);
        return view('car.watchlist', ['cars' => $cars]);
    }
}
