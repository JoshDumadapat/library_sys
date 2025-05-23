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
use App\Http\Controllers\AdminBookRequestController;
use App\Http\Controllers\MemberBookRequestController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\MemberTransactionController;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\MemberPasswordController;
use App\Http\Controllers\AdminSettingsController;

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

    //DASHBOARD CAR ROUTES ivan
    Route::get('admin/managebooks/index', [BookController::class, 'index'])->name('admin.managebooks.index');
    Route::get('/dashboard', [BookController::class, 'dashboard']);


    //REPORT ROUTE CHANGES
    // web.php (routes)
    Route::get('/admin/report', [AdminController::class, 'report'])->name('admin.report'); // Reports route

    // These are already in place for lending-specific reports
    Route::get('admin/report/lending', [AdminReportController::class, 'showLendingReport'])->name('reports.lending');
    Route::get('admin/report/lending/pdf', [AdminReportController::class, 'generateLendingReportPDF'])->name('reports.lending.pdf');
    //END

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
// Route::get('/request-book', function () {
//     return view('member.requestbook'); // This will point to the view/member/requestbook.blade.php file
// });
// Route::get('/book-requests', function () {
//     return view('member.bookrequest'); // This points to resources/views/member/bookrequested.blade.php
// // });
// Route::get('/borrowed-books', function () {
//     return view('member.borrowedbook'); // This points to resources/views/member/bookrequested.blade.php
// });
Route::get('/overdue-books', function () {
    return view('member.borrowedbook'); // This points to resources/views/member/bookrequested.blade.php
});
Route::prefix('member/requests')->group(function () {
    Route::get('/', [MemberBookRequestController::class, 'index'])->name('member.requests.index');
    Route::get('/create', [MemberBookRequestController::class, 'create'])->name('member.requests.create');
    Route::post('/', [MemberBookRequestController::class, 'store'])->name('member.requests.store');
});



//new added

// // Member routes
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::prefix('member/requests')->group(function () {
//         Route::get('/', [MemberBookRequestController::class, 'index'])->name('member.requests.index');
//         Route::get('/create', [MemberBookRequestController::class, 'create'])->name('member.requests.create');
//         Route::post('/', [MemberBookRequestController::class, 'store'])->name('member.requests.store');
//     });
// });

// Admin routes

Route::prefix('admin/requests')->group(function () {
    Route::get('/', [AdminBookRequestController::class, 'index'])->name('admin.requests.index');
    Route::get('/{bookRequest}', [AdminBookRequestController::class, 'show'])->name('admin.requests.show');
    Route::post('/{bookRequest}/approve', [AdminBookRequestController::class, 'approve'])->name('admin.requests.approve');
    Route::post('/{bookRequest}/reject', [AdminBookRequestController::class, 'reject'])->name('admin.requests.reject');
    Route::post('/{bookRequest}/lend', [AdminBookRequestController::class, 'lend'])->name('admin.requests.lend');
});

//Rica added this
Route::get('/fines/generate-report', [FinesController::class, 'generateReport'])->name('fines.generate-report');


// For the print preview route
// PDF Export
Route::get('/reports/lending/pdf', [AdminReportController::class, 'pdf'])->name('reports.lending.pdf');

// Print View
Route::get('/reports/lending/print', [AdminReportController::class, 'print'])->name('reports.lending.print');


//rica aDded
Route::get('/authors/search', [BookController::class, 'searchAuthors'])->name('authors.search');
Route::post('/authors/store', [BookController::class, 'storeAuthor'])->name('authors.store');
Route::get('/genres/search', [BookController::class, 'searchGenres'])->name('genres.search');
Route::post('/genres/store', [BookController::class, 'storeGenre'])->name('genres.store');

//new
Route::middleware(['auth'])->group(function () {
    Route::get('/member/transaction/{id}', [MemberTransactionController::class, 'getLendingDetails'])->name('member.view-lend');
});
Route::get('/member/transaction/{id}', [MemberTransactionController::class, 'viewTransaction'])->name('member.transaction.view');

Route::get('/member/borrowed-book', [MemberTransactionController::class, 'showMyBorrowedBooks'])->name('member.borrowedbooks')->middleware('auth');
Route::get('member/my-borrowed-books', [MemberTransactionController::class, 'myBorrowedBooks'])->name('member.borrowed-books');
Route::get('/member/transaction/{id}', [MemberTransactionController::class, 'viewTransaction']);


//member dashboard card route 
Route::get('/member/dashboard', [MemberDashboardController::class, 'index'])->name('member.dashboard');

//for profile dropdown
Route::get('/member/settings', function () {
    return view('member.settings');
})->name('member.settings')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/member/profile', [MemberProfileController::class, 'edit'])->name('member.settings');
    Route::put('/member/profile', [MemberProfileController::class, 'update'])->name('member.profile.update');
});

// Show the change password form
Route::get('/member/password/change', [MemberPasswordController::class, 'showChangeForm'])->name('member.password.change');

// Handle the change password submission
Route::post('/member/password/change', [MemberPasswordController::class, 'changePassword'])->name('member.password.update');


// Admin Settings Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings/update-profile', [AdminSettingsController::class, 'updateProfile'])->name('admin.settings.profile.update');
    Route::post('/settings/update-fines', [AdminSettingsController::class, 'updateFines'])->name('admin.settings.fines.update');
});
