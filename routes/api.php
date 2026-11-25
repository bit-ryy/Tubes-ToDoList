<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Public Routes (Tanpa Token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Wajib Bearer Token Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Manajemen Proyek, Label, dan Tugas (CRUD Lengkap + Subtask + Pencarian)
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('labels', LabelController::class);
    Route::apiResource('tasks', TaskController::class);

    // Dashboard Statistik
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
