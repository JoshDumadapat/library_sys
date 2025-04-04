<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;

// Homepage
Route::get('/', function () {
    return view('homepage');
});

// Show login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Handle login request
Route::post('/login', [AuthController::class, 'login']);

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Show registration form
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');

// Handle registration request
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes Group
Route::middleware('auth')->group(function () {
    // Admin Dashboard
    // Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');



    // Member Dashboard
    Route::get('/member/dashboard', [MemberController::class, 'index'])->name('member.dashboard');
});
