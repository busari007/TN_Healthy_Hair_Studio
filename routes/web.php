<?php

use Illuminate\Support\Facades\Route;

// Include auth routes(ensures the file works)
require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('homepage');
})->name('home');

Route::get('/admin/users', function () {
    return view('welcome');
})->name('admin.users');
Route::get('/admin', function () {
    return view('bookings');
})->name('admin.booking');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/home', function () {
        return view('homepage');
    });
});
