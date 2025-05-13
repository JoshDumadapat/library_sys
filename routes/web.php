<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FinesController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReturnBookController;
use App\Http\Controllers\PaymentController;

// Homepage
Route::get('/', function () {
    return view('homepage');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes Group
Route::middleware('auth')->group(function () {
    // Admin Dashboard Routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/manage-books', [BookController::class, 'index'])->name('admin.manageBooks');
    Route::get('/admin/lend', [AdminController::class, 'lend'])->name('admin.lend');
    Route::get('/admin/return', [ReturnBookController::class, 'showLendingRecords'])->name('admin.return');
    Route::get('/admin/fine', [FinesController::class, 'index'])->name('admin.fines');
    Route::get('/admin/members', [AdminController::class, 'members'])->name('admin.members');
    Route::get('/admin/report', [AdminController::class, 'report'])->name('admin.report');
    Route::get('/admin/employees', [AdminController::class, 'employees'])->name('admin.employees');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/{trans_id}/update', [ReturnBookController::class, 'updateBookStatus'])->name('return.update');
    Route::get('admin/fines/transaction/{transactionId}', [FinesController::class, 'getTransactionFines'])
        ->name('fines.transaction');

    // Book Management Routes
    Route::resource('admin/managebooks', BookController::class);
    Route::get('/admin/managebooks/{book_id}', [BookController::class, 'edit']);
    Route::put('/admin/managebooks/{book_id}', [BookController::class, 'update']);
    Route::get('admin/managebooks/{book_id}', [BookController::class, 'show']);
    Route::delete('/admin/managebooks/{book_id}', [BookController::class, 'destroy'])->name('managebooks.destroy');

    // Member Management Routes
    Route::get('/admin/members', [AdminMemberController::class, 'index'])->name('admin.members');
    Route::post('/admin/members/{member}/deactivate', [AdminMemberController::class, 'deactivate'])->name('admin.members.deactivate');
    Route::post('/admin/members/{member}/activate', [AdminMemberController::class, 'activate'])->name('admin.members.activate');

    // Employee Management Routes
    Route::get('/admin/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/admin/employees/update/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');



    // Lending Transaction Routes
    Route::get('/admin/lend', [TransactionController::class, 'showLendUI'])->name('admin.lend');
    Route::get('/members/{user_id}', [MemberController::class, 'showMember']);
    Route::get('/books/{book_id}', [BookController::class, 'showBook']);
    Route::get('/transactions/next-id', [TransactionController::class, 'getNextId']);
    Route::post('/transactions/lend', [TransactionController::class, 'lendBooks']);

    // Search Routes
    Route::get('/api/search-members', [MemberController::class, 'searchMembers']);
    Route::get('/api/search-books', [BookController::class, 'searchBooks']);
    Route::get('/transactions/next-id', [TransactionController::class, 'getNextTransactionId']);
    Route::get('/book/{id}', [BookController::class, 'showBook']);

    // Return Book Routes
    Route::prefix('admin/return')->group(function () {
        Route::get('/', [ReturnBookController::class, 'showLendingRecords'])->name('admin.return');
        Route::get('/{trans_id}', [ReturnBookController::class, 'getLendingDetails'])->name('return.detail');
        Route::post('/{trans_id}/process', [ReturnBookController::class, 'processReturn'])->name('return.process');
        Route::post('/{trans_id}/update-status', [ReturnBookController::class, 'updateBookStatus'])->name('update.book.status');
    });

    // API Endpoints for Return Functionality
    Route::get('/lending-details/{trans_id}', [ReturnBookController::class, 'getLendingDetails'])->name('lending.details');
    Route::get('/get-fine-rate/{status}', [ReturnBookController::class, 'getFineRate'])->name('fine.rate');
    Route::post('/return-books', [ReturnBookController::class, 'returnBook'])->name('returnBook');

    // Payment Processing Routes
    Route::prefix('fines')->group(function () {
        Route::post('/pay-now', [PaymentController::class, 'processPayment'])
            ->middleware(['auth', 'verified'])
            ->name('fines.pay-now');
        Route::post('/pay-later', [PaymentController::class, 'markAsPending'])
            ->middleware('auth')
            ->name('fines.pay-later');
        Route::post('/process-bulk', [PaymentController::class, 'processBulkPayment'])->name('fines.bulk-pay');
    });
    Route::post('/payments/process', [PaymentController::class, 'processPayment'])->name('payments.process');
});

// Member Dashboard Route
Route::get('/member/dashboard', [MemberController::class, 'index'])->name('member.dashboard');


// routes/web.php
Route::get('/transactions/check-fines/{memberId}', [TransactionController::class, 'checkFines']);

//NEW ADD
Route::get('/member/dashboard', [App\Http\Controllers\MemberDashboardController::class, 'index'])->name('member.dashboard');
Route::get('/transactions/check-book-availability/{bookId}', [TransactionController::class, 'checkBookAvailability']);


//MEMBER ROUTES
Route::get('/request-book', function () {
    return view('member.requestbook'); // This will point to the view/member/requestbook.blade.php file
});
Route::get('/book-requests', function () {
    return view('member.bookrequest'); // This points to resources/views/member/bookrequested.blade.php
});
Route::get('/borrowed-books', function () {
    return view('member.borrowedbook'); // This points to resources/views/member/bookrequested.blade.php
});
Route::get('/overdue-books', function () {
    return view('member.borrowedbook'); // This points to resources/views/member/bookrequested.blade.php
});
