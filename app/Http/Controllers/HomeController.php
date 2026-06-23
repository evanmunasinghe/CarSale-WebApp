<?php


namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        // //select all cars from the database
        // $cars = Car::get();
        //  only published cars from the database
        // $cars = Car::where('published_at', '!=', null)->get();
        // dump($cars);

        

        return view('home.index');
} 
}
