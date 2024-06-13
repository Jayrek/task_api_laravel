<?php

use App\Http\Controllers\Api\v1\TaskController;
use App\Http\Controllers\Api\v1\CompleteTaskController;
use App\Http\Controllers\Api\v1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::get('/task/{task}', [TaskController::class, 'show']);
        Route::delete('/task/{task}', [TaskController::class, 'destroy']);
        Route::put('/task/{task}', [TaskController::class, 'update']);
        Route::post('/task', [TaskController::class, 'store']);
        Route::put('/task/{task}/complete', CompleteTaskController::class);
    });
});
