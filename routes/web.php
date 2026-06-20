<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

Route::get('/', function () {
    $person = [
        'name' => 'John Doe', 
        'age' => 30
        ];
        dump($person);
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/car', [CarController::class, 'index']);