<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
});

Route::get('/grafik', function () {
    return view('grafik');
});

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
