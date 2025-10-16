<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.form');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register.form');
});
require __DIR__.'/auth.php';