<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\KategoriController;
use App\Http\Controllers\API\AlatController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PeminjamanController;
use App\Http\Controllers\API\PengembalianController;
use App\Http\Controllers\API\LaporanController;
use App\Models\LogAktivitas;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =========================================================
// PUBLIC ROUTES
// =========================================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// =========================================================
// PROTECTED ROUTES (Harus Login / Pakai Bearer Token)
// =========================================================

Route::middleware('auth:sanctum')->group(function () {

    // -----------------------------------------------------
    // PROFIL & AUTH
    // -----------------------------------------------------
    Route::get('/me', function (Request $request) {
        return response()->json($request->user());
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // -----------------------------------------------------
    // PEMINJAMAN & PENGEMBALIAN (Semua Role Login)
    // -----------------------------------------------------
    Route::apiResource('peminjaman', PeminjamanController::class);
    Route::apiResource('pengembalian', PengembalianController::class);

    // -----------------------------------------------------
    // KHUSUS ADMIN
    // -----------------------------------------------------
    Route::middleware('role.admin')->group(function () {

        // CRUD Master Data
        Route::apiResource('kategori', KategoriController::class);
        Route::apiResource('alat', AlatController::class);
        Route::apiResource('users', UserController::class);

        // Laporan Peminjaman
        Route::get('/laporan-peminjaman', [LaporanController::class, 'index']);

        // Log Aktivitas
        Route::get('/log-aktivitas', function () {
            $logs = LogAktivitas::latest()->paginate(10);
            return response()->json($logs);
        });
    });

});