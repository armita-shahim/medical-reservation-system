<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/myProfile', [AuthController::class, 'myProfile']);

    Route::get('/specialties', [SpecialtyController::class, 'index']);
    Route::get('/hospitals', [HospitalController::class, 'index']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/specialties', [SpecialtyController::class, 'store']);
        Route::patch('/specialties/{specialty}', [SpecialtyController::class, 'update']);
        Route::post('/hospitals', [HospitalController::class, 'store']);
        Route::patch('/hospitals/{hospital}', [HospitalController::class, 'update']);
    });
});
