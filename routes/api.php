<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route CRUD untuk resource 'api_data'
Route::prefix('data')->group(function () {
    Route::get('/', [APIController::class, 'index']);         // GET /api/data - Menampilkan semua data
    Route::post('/', [APIController::class, 'store']);        // POST /api/data - Menyimpan data baru
    Route::get('/{id}', [APIController::class, 'show']);      // GET /api/data/{id} - Menampilkan data berdasarkan ID
    Route::put('/{id}', [APIController::class, 'update']);    // PUT /api/data/{id} - Memperbarui data berdasarkan ID
    Route::delete('/{id}', [APIController::class, 'destroy']); // DELETE /api/data/{id} - Menghapus data berdasarkan ID
});