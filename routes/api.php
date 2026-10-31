<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpecialtyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\AvailabilityExceptionController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\AppointmentController;

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
    Route::get('/doctors/{doctor}/slots', [SlotController::class, 'index']);

    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->can('view', 'appointment');
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->can('cancel', 'appointment');
    Route::patch('/doctors/{doctor}', [DoctorController::class, 'update'])->can('update', 'doctor');


    Route::middleware('role:patient')->group(function () {
        Route::post('/appointments', [AppointmentController::class, 'store']);
    });

    Route::middleware('role:doctor')->group(function () {

        Route::get('/availabilities', [AvailabilityController::class, 'index']);
        Route::get('/availabilities/{availability}', [AvailabilityController::class, 'show']);
        Route::post('/availabilities', [AvailabilityController::class, 'store']);
        Route::put('/availabilities/{availability}', [AvailabilityController::class, 'update'])->can('update', 'availability');
        Route::delete('/availabilities/{availability}', [AvailabilityController::class, 'destroy'])->can('delete', 'availability');

        Route::get('/availability-exceptions', [AvailabilityExceptionController::class, 'index']);
        Route::get('/availability-exceptions/{exception}', [AvailabilityExceptionController::class, 'show']);
        Route::post('/availability-exceptions', [AvailabilityExceptionController::class, 'store']);
        Route::delete('/availability-exceptions/{exception}', [AvailabilityExceptionController::class, 'destroy'])->can('delete', 'exception');
    });

    Route::middleware('role:doctor/admin')->group(function () {
        Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->can('updateStatus', 'appointment');
    });


    Route::middleware('role:admin')->group(function () {

        Route::post('/specialties', [SpecialtyController::class, 'store']);
        Route::put('/specialties/{specialty}', [SpecialtyController::class, 'update']);

        Route::post('/hospitals', [HospitalController::class, 'store']);
        Route::put('/hospitals/{hospital}', [HospitalController::class, 'update']);

        Route::post('/doctors', [DoctorController::class, 'store']);
        Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::patch('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
});
