<?php

use App\Http\Controllers\AiResponseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CoachAnalyticsController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

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

Route::post('/ai-response', [AiResponseController::class, 'getAIResponse']);

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Client Management (Coach-only)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/clients', [ClientController::class, 'store']); // Create client
    Route::get('/clients', [ClientController::class, 'index']); // List all clients
    Route::put('/clients/{id}', [ClientController::class, 'update']); // Update client
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']); // Delete client
});

// Session Management
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/sessions', [SessionController::class, 'store']);
    Route::get('/sessions', [SessionController::class, 'index']);
    Route::put('/sessions/{id}', [SessionController::class, 'update']);
    Route::delete('/sessions/{id}', [SessionController::class, 'destroy']);
    Route::get('sessions/uncompleted', [SessionController::class, 'getUncompletedSessions']);
    Route::patch('sessions/{sessionId}/complete', [SessionController::class, 'markAsCompleted']);

});

Route::middleware('auth:sanctum')->get('/coach/analytics', [CoachAnalyticsController::class, 'index']);
