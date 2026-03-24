<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServicesController;

//Auth Routes

// Only guests (logged-out users) can see these
Route::middleware(['guest'])->group(function () {
Route::get('/signin',[AuthController::class, 'signin'])->name('signin');
Route::get('/signup',[AuthController::class, 'signup'])->name('signup');

Route::post('/signin',[AuthController::class, 'login'])->name('login');
Route::post('/signup',[AuthController::class, 'register'])->name('register');
});


Route::middleware(['auth', 'role:admin,staff,client'])->group(function () {
    Route::get('/book-a-service', function () {return view('services');})->name('services');

    Route::get('/api/bookings/booked-dates', [ServicesController::class, 'bookedDates']);
    Route::get('/api/bookings/check-staff-availability', [ServicesController::class, 'checkStaffAvailability']);
    Route::get('/api/bookings/booked-times', [ServicesController::class, 'bookedTimes']);

    Route::get('/bookings/list', [ServicesController::class, 'getBookings'])->name('bookings');

    Route::post('/bookings/store', [ServicesController::class, 'store']);
});

Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::post('/bookings/{id}/status', [ServicesController::class, 'updateStatus'])->name('bookings.updateStatus');
});


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth');
