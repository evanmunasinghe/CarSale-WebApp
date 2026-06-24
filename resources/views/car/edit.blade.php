<x-app-layout title="Edit Car Details">
    <main>
        <div class="container-small">
            <h1 class="car-details-page-title">Edit car details</h1>
            
            <form action="{{ route('car.update', $car) }}" method="POST" enctype="multipart/form-data" class="card add-new-car-form">
                @csrf
                @method('PUT')
                @if ($errors->any())
                    <div class="alert alert-danger mb-medium">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <div class="form-content">
                    <div class="form-details">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="maker_id">Maker</label>
                                    <select name="maker_id" id="maker_id" required>
                                        <option value="">Select Maker</option>
                                        @foreach($makers as $maker)
                                            <option value="{{ $maker->id }}" {{ old('maker_id', $car->maker_id) == $maker->id ? 'selected' : '' }}>
                                                {{ $maker->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('maker_id')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-group">
                                    <label for="model_id">Model</label>
                                    <select name="model_id" id="model_id" required>
                                        <option value="">Select Model</option>
                                    </select>
                                    @error('model_id')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-group">
                                    <label for="year">Year</label>
                                    <select name="year" id="year" required>
                                        <option value="">Year</option>
                                        @for ($y = date('Y'); $y >= 1990; $y--)
                                            <option value="{{ $y }}" {{ old('year', $car->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                        @endfor
                                    </select>
                                    @error('year')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Car Type</label>
                            <div class="row">
                                @foreach($carTypes as $type)
                                    <div class="col">
                                        <label class="inline-radio">
                                            <input type="radio" name="car_type_id" value="{{ $type->id }}" {{ old('car_type_id', $car->car_type_id) == $type->id ? 'checked' : '' }} required />
                                            {{ $type->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('car_type_id')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="price">Price ($)</label>
                                    <input type="number" id="price" name="price" value="{{ old('price', $car->price) }}" placeholder="Price" required />
                                    @error('price')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="vin">Vin Code</label>
                                    <input id="vin" name="vin" value="{{ old('vin', $car->vin) }}" placeholder="Vin Code" required />
                                    @error('vin')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="mileage">Mileage (ml)</label>
                                    <input type="number" id="mileage" name="mileage" value="{{ old('mileage', $car->mileage) }}" placeholder="Mileage" />
                                    @error('mileage')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Fuel Type</label>
                            <div class="row">
                                @foreach($fuelTypes as $fuel)
                                    <div class="col">
                                        <label class="inline-radio">
                                            <input type="radio" name="fuel_type_id" value="{{ $fuel->id }}" {{ old('fuel_type_id', $car->fuel_type_id) == $fuel->id ? 'checked' : '' }} required />
                                            {{ $fuel->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('fuel_type_id')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="state_id">State/Region</label>
                                    <select id="state_id">
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ old('state_id', $currentStateId) == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col">
                                <div class="form-group">
                                    <label for="city_id">City</label>
                                    <select name="city_id" id="city_id" required>
                                        <option value="">Select City</option>
                                    </select>
                                    @error('city_id')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input id="address" name="address" value="{{ old('address', $car->address) }}" placeholder="Address" required />
                                    @error('address')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input id="phone" name="phone" value="{{ old('phone', $car->phone) }}" placeholder="Phone" required />
                                    @error('phone')
                                        <p class="error-message">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Features</label>
                            <div class="row">
                                <div class="col">
                                    <label class="checkbox"><input type="checkbox" name="air_conditioning" value="1" {{ old('air_conditioning', $car->features?->air_conditioning) ? 'checked' : '' }} /> Air Conditioning</label>
                                    <label class="checkbox"><input type="checkbox" name="power_windows" value="1" {{ old('power_windows', $car->features?->power_windows) ? 'checked' : '' }} /> Power Windows</label>
                                    <label class="checkbox"><input type="checkbox" name="power_door_locks" value="1" {{ old('power_door_locks', $car->features?->power_door_locks) ? 'checked' : '' }} /> Power Door Locks</label>
                                    <label class="checkbox"><input type="checkbox" name="abs" value="1" {{ old('abs', $car->features?->abs) ? 'checked' : '' }} /> ABS</label>
                                    <label class="checkbox"><input type="checkbox" name="cruise_control" value="1" {{ old('cruise_control', $car->features?->cruise_control) ? 'checked' : '' }} /> Cruise Control</label>
                                    <label class="checkbox"><input type="checkbox" name="bluetooth_connectivity" value="1" {{ old('bluetooth_connectivity', $car->features?->bluetooth_connectivity) ? 'checked' : '' }} /> Bluetooth Connectivity</label>
                                </div>
                                <div class="col">
                                    <label class="checkbox"><input type="checkbox" name="remote_start" value="1" {{ old('remote_start', $car->features?->remote_start) ? 'checked' : '' }} /> Remote Start</label>
                                    <label class="checkbox"><input type="checkbox" name="gps_navigation" value="1" {{ old('gps_navigation', $car->features?->gps_navigation) ? 'checked' : '' }} /> GPS Navigation System</label>
                                    <label class="checkbox"><input type="checkbox" name="heater_seats" value="1" {{ old('heater_seats', $car->features?->heater_seats) ? 'checked' : '' }} /> Heated Seats</label>
                                    <label class="checkbox"><input type="checkbox" name="climate_control" value="1" {{ old('climate_control', $car->features?->climate_control) ? 'checked' : '' }} /> Climate Control</label>
                                    <label class="checkbox"><input type="checkbox" name="rear_parking_sensors" value="1" {{ old('rear_parking_sensors', $car->features?->rear_parking_sensors) ? 'checked' : '' }} /> Rear Parking Sensors</label>
                                    <label class="checkbox"><input type="checkbox" name="leather_seats" value="1" {{ old('leather_seats', $car->features?->leather_seats) ? 'checked' : '' }} /> Leather Seats</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Detailed Description</label>
                            <textarea id="description" name="description" rows="10">{{ old('description', $car->description) }}</textarea>
                            @error('description')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="checkbox">
                                <input type="checkbox" name="published" value="1" {{ old('published', $car->published_at) ? 'checked' : '' }} />
                                Published
                            </label>
                        </div>
                    </div>

                    <div class="form-images">
                        <div class="form-image-upload">
                            <div class="upload-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 48px">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <input id="carFormImageUpload" name="images[]" type="file" multiple />
                        </div>
                        @error('images')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                        <div id="imagePreviews" class="car-form-images">
                            @foreach($car->images as $img)
                                <div class="car-form-image-preview">
                                    <img src="{{ $img->url }}" alt="" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="p-medium" style="width: 100%">
                    <div class="flex justify-end gap-1">
                        <button type="button" class="btn btn-default" onclick="window.location.reload();">Reset Changes</button>
                        <button type="submit" class="btn btn-primary">Update Car</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        const makersData = @json($makers);
        const statesData = @json($states);

        document.getElementById('maker_id').addEventListener('change', function () {
            const selectedMakerId = this.value;
            const modelSelect = document.getElementById('model_id');
            modelSelect.innerHTML = '<option value="">Select Model</option>';

            const maker = makersData.find(m => m.id == selectedMakerId);
            if (maker && maker.models) {
                maker.models.forEach(model => {
                    const opt = document.createElement('option');
                    opt.value = model.id;
                    opt.textContent = model.name;
                    modelSelect.appendChild(opt);
                });
            }
        });

        document.getElementById('state_id').addEventListener('change', function () {
            const selectedStateId = this.value;
            const citySelect = document.getElementById('city_id');
            citySelect.innerHTML = '<option value="">Select City</option>';

            const state = statesData.find(s => s.id == selectedStateId);
            if (state && state.cities) {
                state.cities.forEach(city => {
                    const opt = document.createElement('option');
                    opt.value = city.id;
                    opt.textContent = city.name;
                    citySelect.appendChild(opt);
                });
            }
        });

        // 4. Critical additions for Edit view hydration on DOM load
        window.addEventListener('DOMContentLoaded', () => {
            // Re-render and select current model on page load
            const initialMakerId = "{{ old('maker_id', $car->maker_id) }}";
            if (initialMakerId) {
                const makerSelect = document.getElementById('maker_id');
                makerSelect.value = initialMakerId;
                makerSelect.dispatchEvent(new Event('change'));
                
                document.getElementById('model_id').value = "{{ old('model_id', $car->model_id) }}";
            }

            // Re-render and select current city on page load
            const initialStateId = "{{ old('state_id', $currentStateId) }}";
            if (initialStateId) {
                const stateSelect = document.getElementById('state_id');
                stateSelect.value = initialStateId;
                stateSelect.dispatchEvent(new Event('change'));
                
                document.getElementById('city_id').value = "{{ old('city_id', $car->city_id) }}";
            }
        });
    </script>
</x-app-layout>
