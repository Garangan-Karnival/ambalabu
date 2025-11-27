<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WeatherController; 
use App\Http\Controllers\SensorController;
use App\Http\Controllers\ProfileController;


/*
|--------------------------------------------------------------------------
| Public Routes (Akses publik tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', [WeatherController::class, 'currentWeather'])->name('home');

// Login & register page
Route::get('/login', function () {
    return view('regislog');
})->name('login.page');

// Auth routes
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Sensor Input (Harus diakses oleh ESP8266, jadi harus di luar middleware 'auth')
Route::get('/input_data', [SensorController::class, 'input']);
Route::post('/input_data', [SensorController::class, 'input']);


/*
|--------------------------------------------------------------------------
| Protected Routes (Akses hanya untuk user yang sudah login)
|--------------------------------------------------------------------------
*/

    Route::get('/password/change', function () {
        return view('change-password');
    })->name('password.change');

    // PROSES UPDATE PASSWORD
    Route::post('/password/change', [AuthController::class, 'changePassword'])
        ->name('password.update');

Route::middleware(['auth'])->group(function () {
    
    // Profile
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Grafik (HARUS MENGGUNAKAN CONTROLLER UNTUK MEMUAT DATA SENSOR YANG DIFILTER)
    Route::get('/grafik', [SensorController::class, 'showGrafik'])->name('grafik');
    
    // Weather Routes
    Route::get('/weather', [WeatherController::class, 'currentWeather']);
    Route::get('/weather/forecast', [WeatherController::class, 'forecast']);
    Route::get('/weather-refresh', [WeatherController::class, 'fetchWeather']);
});