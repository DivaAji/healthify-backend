<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AgeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ProfileController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return response()->json($request->user());
});


// routes/api.php
Route::post('login', [LoginController::class, 'login']);

// Route CRUD untuk resource 'user'
Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']);         
    Route::post('/', [UserController::class, 'store']);        
    Route::get('/{id}', [UserController::class, 'show']);     
    
    // Pastikan route ini menggunakan middleware auth:api untuk memverifikasi JWT
    Route::put('/{id}', [UserController::class, 'update'])->middleware('auth:api');    
    Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('auth:api'); 
});

Route::post('upload-image', [ImageController::class, 'uploadImage']);
Route::post('submit-age-manual', [UserController::class, 'submitAge']);


Route::middleware('auth:api')->get('/profile', [ProfileController::class, 'show']);
Route::middleware('auth:api')->put('/profile', [ProfileController::class, 'update']);

Route::get('/workouts', [WorkoutController::class, 'getWorkoutsByAge']);