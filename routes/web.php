<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Include auth routes(ensures the file works)
require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('homepage');
})->name('home');

Route::middleware(['auth', 'role:admin'])->group(function () {
Route::get('/admin/users', function () {
    return view('users');
})->name('admin.users');

Route::get('/admin', function () {
    return view('bookings');
})->name('admin.booking');

Route::get('/users', [UserController::class, 'getUsers'])->name('users.index');
Route::post('/users/{id}/status', [UserController::class, 'updateStatus'])->name('users.status');

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/home', function () {
        return view('homepage');
    });
});

Route::middleware(['auth'])->group(function () {
Route::post('/bookings/{id}/refund', [ServicesController::class, 'refund']);

Route::post('/api/payments/init', [PaymentController::class, 'initialize']);

Route::get('/payment/callback', function () {
    return redirect()->route('home')->with('success', 'Payment successful! Your booking is being processed.');
})->name('payment.callback');


Route::get('/payment', function () {
    return view('payment');
})->name('payment');
});


Route::post('/payments/webhook', [PaymentController::class, 'webhook']);