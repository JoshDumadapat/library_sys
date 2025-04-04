<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('homepage');
});

// Show login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Handle login request
Route::post('/login', [AuthController::class, 'login']);

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected dashboard route (only logged-in users can access)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Show registration form
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Handle registration request
Route::post('/register', [AuthController::class, 'register']);
