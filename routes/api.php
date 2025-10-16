<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'guest'], function () {
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
    // Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
