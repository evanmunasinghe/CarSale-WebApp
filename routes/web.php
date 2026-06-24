<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CarController;


Route::get('/', [HomeController::class,'index']) ->name('home');

Route::get('/signup', [SignupController::class,'create']) ->name('signup');

Route::post('/signup', [SignupController::class,'store']) ->name('signup.submit');

Route::post('/login', [LoginController::class, 'store'])->name('login.submit');

Route::get('/login', [LoginController::class,'create']) ->name('login');

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

Route::get('/car/search', [CarController::class,'search']) ->name('car.search');

Route::post('/car/search', [CarController::class, 'searchSubmit']) ->name('car.search.submit');

Route::get('/car/watchlist', [CarController::class,'watchlist']) ->name('car.watchlist')->middleware('auth');

Route::resource('car', CarController::class)->except(['show'])->middleware('auth');

Route::get('/car/{car}', [CarController::class, 'show'])->name('car.show');

Route::get('/about', function () {
    return view('about');
});
