<?php

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
