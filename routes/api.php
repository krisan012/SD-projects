<?php

use App\Http\Controllers\Api\AssignProjectController;
use App\Http\Controllers\Api\CreateProjectController;
use App\Http\Controllers\Api\UpdateProjectController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DeleteProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('project', CreateProjectController::class)->name('project.store');
    Route::post('project/update/{project}', UpdateProjectController::class)->name('project.update');
    Route::post('project/delete/{project}', DeleteProjectController::class)->name('project.delete');
    Route::post('project/assign/{project}', AssignProjectController::class)->name('project.assign');
});