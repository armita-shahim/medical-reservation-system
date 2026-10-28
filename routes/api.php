<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);

    Route::get('/specialties', [SpecialtyController::class, 'index']);
    Route::get('/specialties/{specialty}', [SpecialtyController::class, 'show']);

    Route::get('/hospitals', [HospitalController::class, 'index']);
    Route::get('/hospitals/{hospital}', [HospitalController::class, 'show']);

    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);
    Route::patch('/doctors/{doctor}', [DoctorController::class, 'update']);


    Route::middleware('role:admin')->group(function () {

        Route::post('/specialties', [SpecialtyController::class, 'store']);
        Route::patch('/specialties/{specialty}', [SpecialtyController::class, 'update']);

        Route::post('/hospitals', [HospitalController::class, 'store']);
        Route::patch('/hospitals/{hospital}', [HospitalController::class, 'update']);

        Route::post('/doctors', [DoctorController::class, 'store']);
        Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::patch('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
});
