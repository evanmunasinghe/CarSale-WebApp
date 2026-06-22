<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CarController;


Route::get('/', [HomeController::class,'index']) ->name('home');

Route::get('/signup', [SignupController::class,'create']) ->name('signup');

Route::get('/login', [LoginController::class,'create']) ->name('login');

Route::get('/car/search', [CarController::class,'search']) ->name('car.search');
Route::resource('car', CarController::class);

Route::get('/about', function () {
    return view('about');
});

