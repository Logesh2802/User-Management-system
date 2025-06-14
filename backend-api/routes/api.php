<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'getUser']);

    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    
    Route::get('/admin/users/search', [AdminController::class, 'search']);

    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'index']);
        Route::get('/admin/users/{id}', [AdminController::class, 'show']);
        Route::post('/admin/users', [AdminController::class, 'store']);
        Route::put('/admin/users/{id}', [AdminController::class, 'update']);
        Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
        
    });
        

});
