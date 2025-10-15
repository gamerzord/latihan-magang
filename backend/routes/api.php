<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/users', [UserController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
  Route::apiResource('products', controller: ProductController::class);
  Route::apiResource('users', UserController::class)->except(['store']);
  Route::post('/logout', [AuthController::class, 'logout']);
  Route::apiResource('divisions', DivisionController::class);
  Route::get('divisions-active', [DivisionController::class, 'active']);
  Route::apiResource('positions', PositionController::class);
  Route::get('positions-active', [PositionController::class, 'active']);
  Route::apiResource('employees', EmployeeController::class);
});
