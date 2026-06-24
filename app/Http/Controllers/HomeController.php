<?php


namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarType;
use App\Models\FuelType;
use App\Models\Maker;
use App\Models\State;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
       
        $cars = Car::where('published_at', '<=', now())
        ->with(['primaryImage','city','carType','fuelType','maker','model'])
        ->orderBy('published_at','desc')
        ->limit(30)
        ->get();

        $makers = Maker::with('models')->orderBy('name', 'asc')->get();
        $carTypes = CarType::orderBy('name', 'asc')->get();
        $fuelTypes = FuelType::orderBy('name', 'asc')->get();
        $states = State::with('cities')->orderBy('name', 'asc')->get();
        

        return view('home.index', compact('cars', 'makers', 'carTypes', 'fuelTypes', 'states'));
} 
}
