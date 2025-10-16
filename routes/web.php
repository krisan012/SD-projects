<?php

use App\Http\Controllers\ListProjectController;
use App\Http\Controllers\ShowProjectController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login.form');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register.form');
    
    Route::get('/', function () {
        return view('welcome');
    });
});

Route::group(['middleware' => 'auth'], function(){
    Route::prefix('project')->group(function(){
        Route::get('create', function(){
            return view('project.create');
        })->name('project.create');

        Route::get('{project}', ShowProjectController::class)->name('project.show');
    });

    Route::get('/', ListProjectController::class);
});

require __DIR__.'/auth.php';