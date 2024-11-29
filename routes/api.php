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
|---------------------------------------------------------------------------
| API Routes
|---------------------------------------------------------------------------
| Here is where you can register API routes for your application.
*/

Route::post('/user', [UserController::class, 'store']); // Rute untuk registrasi (tanpa autentikasi)

Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index']); // Rute untuk mengambil daftar pengguna (tanpa autentikasi)
    Route::get('/{id}', [UserController::class, 'show']); // Rute untuk mengambil data pengguna (tanpa autentikasi)
    Route::put('/{id}', [UserController::class, 'update'])->middleware('auth:api'); // Rute untuk memperbarui data pengguna (perlu autentikasi)
    Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('auth:api'); // Rute untuk menghapus pengguna (perlu autentikasi)
});

// Rute untuk login
// Route::post('/login', [LoginController::class, 'login'])->name('login');

// Rute untuk upload gambar dan submit umur manual (tanpa autentikasi)
Route::post('upload-image', [ImageController::class, 'uploadImage']);
Route::post('submit-age-manual', [UserController::class, 'submitAge']);

// Rute untuk profil yang memerlukan autentikasi
Route::middleware('auth:api')->get('/profile', [ProfileController::class, 'show']);
Route::middleware('auth:api')->put('/profile', [ProfileController::class, 'update']);

// Rute untuk workout (tanpa autentikasi)
Route::get('/workouts', [WorkoutController::class, 'getWorkoutsByAge']);
