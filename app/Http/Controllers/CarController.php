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

    public function searchAjax(Request $request)
{
    $filters = array_filter($request->only([
        'maker_id',
        'model_id',
        'car_type_id',
        'year_from',
        'year_to',
        'price_from',
        'price_to',
        'mileage',
        'state_id',
        'city_id',
        'fuel_type_id',
        'sort',
    ]), fn ($value) => $value !== null && $value !== '');

    $query = Car::where('published_at', '<=', now())
        ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model']);

    // apply same filters here
    // example:
    if (!empty($filters['maker_id'])) {
        $query->where('maker_id', $filters['maker_id']);
    }

    $cars = $query->orderBy('published_at', 'desc')->paginate(15);

    return response()->json([
        'html' => view('car.partials.search-results', compact('cars'))->render(),
    ]);
}
    public function search()
    {
        $filters = session('car_search_filters', []);

        $query = Car::where('published_at', '<=', now())
            ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model']);

        if (!empty($filters['maker_id'])) {
            $query->where('maker_id', $filters['maker_id']);
        }

        if (!empty($filters['model_id'])) {
            $query->where('model_id', $filters['model_id']);
        }

        if (!empty($filters['car_type_id'])) {
            $query->where('car_type_id', $filters['car_type_id']);
        }

        if (!empty($filters['fuel_type_id'])) {
            $query->where('fuel_type_id', $filters['fuel_type_id']);
        }

        if (!empty($filters['city_id'])) {
            $query->where('city_id', $filters['city_id']);
        } elseif (!empty($filters['state_id'])) {
            $query->whereHas('city', function ($cityQuery) use ($filters) {
                $cityQuery->where('state_id', $filters['state_id']);
            });
        }

        if (!empty($filters['year_from'])) {
            $query->where('year', '>=', $filters['year_from']);
        }

        if (!empty($filters['year_to'])) {
            $query->where('year', '<=', $filters['year_to']);
        }

        if (!empty($filters['price_from'])) {
            $query->where('price', '>=', $filters['price_from']);
        }

        if (!empty($filters['price_to'])) {
            $query->where('price', '<=', $filters['price_to']);
        }

        if (!empty($filters['mileage'])) {
            $query->where('mileage', '<=', $filters['mileage']);
        }

        match ($filters['sort'] ?? null) {
            'price' => $query->orderBy('price'),
            '-price' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('published_at', 'desc'),
        };

        $cars = $query->paginate(15);
        $makers = Maker::with('models')->orderBy('name', 'asc')->get();
        $carTypes = CarType::orderBy('name', 'asc')->get();
        $fuelTypes = FuelType::orderBy('name', 'asc')->get();
        $states = State::with('cities')->orderBy('name', 'asc')->get();

        return view('car.search', compact('cars', 'filters', 'makers', 'carTypes', 'fuelTypes', 'states'));
    }

    public function searchSubmit(Request $request)
    {
        $filters = array_filter($request->only([
            'maker_id',
            'model_id',
            'car_type_id',
            'year_from',
            'year_to',
            'price_from',
            'price_to',
            'mileage',
            'state_id',
            'city_id',
            'fuel_type_id',
            'sort',
        ]), fn ($value) => $value !== null && $value !== '');

        if (empty($filters)) {
            session()->forget('car_search_filters');
        } else {
            session(['car_search_filters' => $filters]);
        }

        return redirect()->route('car.search');
    }

    public function watchlist(Request $request)
    {
        $cars = $request->user()->favouriteCars()
            ->with(['primaryImage', 'city', 'carType', 'fuelType', 'maker', 'model'])
            ->paginate(15);
        return view('car.watchlist', ['cars' => $cars]);
    }
}
