<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PsychologistController;
use App\Http\Controllers\TimeSlotController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public Routes
Route::post('/psychologists', [PsychologistController::class, 'store']); // Register psychologist
Route::post('/psychologists/login', [PsychologistController::class, 'login']); // Login endpoint

// Authenticated Routes
Route::middleware([
    EnsureFrontendRequestsAreStateful::class, // Sanctum SPA middleware
    'auth:sanctum', // Require authentication
])->group(function () {
    // Psychologists
    Route::get('/psychologists', [PsychologistController::class, 'index']);
    Route::post('/psychologists/logout', [PsychologistController::class, 'logout']); // Logout endpoint

    // Time Slots
    Route::post('/psychologists/{id}/time-slots', [TimeSlotController::class, 'store']); // Add time slots
    Route::get('/psychologists/{id}/time-slots', [TimeSlotController::class, 'index']);  // View available time slots
    Route::put('/time-slots/{id}', [TimeSlotController::class, 'update']);              // Update time slot
    Route::delete('/time-slots/{id}', [TimeSlotController::class, 'destroy']);           // Delete time slot

    // Appointments
    Route::post('/appointments', [AppointmentController::class, 'store']); // Book an appointment
    Route::get('/appointments', [AppointmentController::class, 'index']);  // Retrieve upcoming appointments
});
