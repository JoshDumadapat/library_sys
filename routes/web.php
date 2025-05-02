<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReturnBookController;


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
    // Admin Routes
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/manage-books', [BookController::class, 'index'])->name('admin.manageBooks');
    Route::get('/admin/lend', [AdminController::class, 'lend'])->name('admin.lend');
    Route::get('/admin/return', [AdminController::class, 'return'])->name('admin.return');
    Route::get('/admin/members', [AdminController::class, 'members'])->name('admin.members');
    Route::get('/admin/report', [AdminController::class, 'report'])->name('admin.report');
    Route::get('/admin/employees', [AdminController::class, 'employees'])->name('admin.employees');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

    // Member Routes
    Route::get('/member/dashboard', [MemberController::class, 'index'])->name('member.dashboard');

    // Resource route for the books (for create, edit, update, store, destroy)
    Route::resource('admin/managebooks', BookController::class);

    // Additional specific routes for books (to fix duplicate routes)
    Route::get('/admin/managebooks/{book_id}', [BookController::class, 'edit']); // Fetch book data for editing
    Route::put('/admin/managebooks/{book_id}', [BookController::class, 'update']); // Update the book data
    Route::get('admin/managebooks/{book_id}', [BookController::class, 'show']);
    Route::delete('/admin/managebooks/{book_id}', [BookController::class, 'destroy'])->name('managebooks.destroy'); // Delete a book
});

// Route for displaying the members list
Route::get('/admin/members', [AdminMemberController::class, 'index'])->name('admin.members');

// Route for deactivating a member
Route::post('/admin/members/{member}/deactivate', [AdminMemberController::class, 'deactivate'])->name('admin.members.deactivate');

// Route for activating a member
Route::post('/admin/members/{member}/activate', [AdminMemberController::class, 'activate'])->name('admin.members.activate');



Route::get('/admin/employees/edit/{id}', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
Route::put('/admin/employees/update/{id}', [EmployeeController::class, 'update'])->name('admin.employees.update');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');


Route::get('/admin/lend', [TransactionController::class, 'showLendUI'])->name('admin.lend');

// For AJAX data fetch
Route::get('/members/{user_id}', [MemberController::class, 'showMember']);
Route::get('/books/{book_id}', [BookController::class, 'showBook']);
Route::get('/transactions/next-id', [TransactionController::class, 'getNextId']);

// Final lending submission
Route::post('/transactions/lend', [TransactionController::class, 'lendBooks']);

// For searching members and books
Route::get('/api/search-members', [MemberController::class, 'searchMembers']);
Route::get('/api/search-books', [BookController::class, 'searchBooks']);
Route::get('/transactions/next-id', [TransactionController::class, 'getNextTransactionId']);

Route::get('/book/{id}', [BookController::class, 'showBook']);



// This is where i started of returning of books

Route::post('/return-book', [ReturnBookController::class, 'returnBook'])->name('returnBook');
Route::get('return-books', [ReturnBookController::class, 'showLendingRecords'])->name('admin.return');

// Route::get('/admin/return/{trans_id}', [ReturnBookController::class, 'showLendingDetail'])->name('return.detail');
Route::post('/admin/return/{trans_id}/update-status', [ReturnBookController::class, 'updateBookStatus'])->name('update.book.status');
Route::get('/admin/return/{trans_id}', [ReturnBookController::class, 'returnBook'])->name('return.book.detail');
// Route::get('/admin/return/{trans_id}', [ReturnBookController::class, 'showLendingDetails'])->name('return.book.detail');

//IVAN ADDED THIS 



Route::get('/lending-details/{trans_id}', [ReturnBookController::class, 'getLendingDetails'])->name('lending.details');
Route::get('/get-fine-rate/{reason}', function ($reason) {
    $fine = \App\Models\FineType::where('reason', ucfirst($reason))->value('fine_amt');
    return response()->json(['fine_amt' => $fine ?? 0]);
});
Route::post('/update-book-status/{trans_id}', [ReturnBookController::class, 'updateBookStatus']);











