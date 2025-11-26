<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WeatherController; 

Route::get('/', [WeatherController::class, 'currentWeather'])->name('home');

Route::get('/grafik', function () {
    return view('grafik');
})->name('grafik');

// Login & register page
Route::get('/login', function () {
    return view('regislog');
})->name('login.page');

// Profile (protected)
Route::get('/profile', function () {
    return view('profile');
})->middleware('auth')->name('profile');

// Auth routes
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Weather routes

Route::get('/weather', [WeatherController::class, 'currentWeather']);
Route::get('/weather/forecast', [WeatherController::class, 'forecast']);
Route::get('/weather-refresh', [WeatherController::class, 'fetchWeather']);