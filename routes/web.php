<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WEB\PengembalianController;


/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | PROFILE ADMIN
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [AdminController::class, 'profile'])
            ->name('profile');

        Route::put('/profile', [AdminController::class, 'updateProfile'])
            ->name('profile.update');

        Route::post('/profile/foto', [AdminController::class, 'updateFoto'])
            ->name('profile.updateFoto');


        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [AdminController::class, 'indexUser'])
            ->name('user.index');

        Route::get('/users/create', [AdminController::class, 'createUser'])
            ->name('user.create');

        Route::post('/users', [AdminController::class, 'storeUser'])
            ->name('user.store');

        Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])
            ->name('user.edit');

        Route::put('/users/{id}', [AdminController::class, 'updateUser'])
            ->name('user.update');

        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])
            ->name('user.destroy');


        /*
        |--------------------------------------------------------------------------
        | KATEGORI
        |--------------------------------------------------------------------------
        */

        Route::get('/kategori', [AdminController::class, 'indexKategori'])
            ->name('kategori.index');

        Route::get('/kategori/create', [AdminController::class, 'createKategori'])
            ->name('kategori.create');

        Route::post('/kategori', [AdminController::class, 'storeKategori'])
            ->name('kategori.store');

        Route::get('/kategori/{id}/edit', [AdminController::class, 'editKategori'])
            ->name('kategori.edit');

        Route::put('/kategori/{id}', [AdminController::class, 'updateKategori'])
            ->name('kategori.update');

        Route::delete('/kategori/{id}', [AdminController::class, 'destroyKategori'])
            ->name('kategori.destroy');


        /*
        |--------------------------------------------------------------------------
        | ALAT
        |--------------------------------------------------------------------------
        */

        Route::get('/alat', [AdminController::class, 'indexAlat'])
            ->name('alat.index');

        Route::get('/alat/create', [AdminController::class, 'createAlat'])
            ->name('alat.create');

        Route::post('/alat', [AdminController::class, 'storeAlat'])
            ->name('alat.store');

        Route::get('/alat/{id}/edit', [AdminController::class, 'editAlat'])
            ->name('alat.edit');

        Route::put('/alat/{id}', [AdminController::class, 'updateAlat'])
            ->name('alat.update');

        Route::delete('/alat/{id}', [AdminController::class, 'destroyAlat'])
            ->name('alat.destroy');


        /*
        |--------------------------------------------------------------------------
        | PEMINJAMAN
        |--------------------------------------------------------------------------
        */

        // Menampilkan daftar peminjaman
        Route::get('/peminjaman', [AdminController::class, 'indexPeminjaman'])
            ->name('peminjaman.index');

        // Form tambah peminjaman
        Route::get('/peminjaman/create', [AdminController::class, 'createPeminjaman'])
            ->name('peminjaman.create');

        // Menyimpan peminjaman baru
        Route::post('/peminjaman', [AdminController::class, 'storePeminjaman'])
            ->name('peminjaman.store');

        // Mengubah status peminjaman
        Route::put('/peminjaman/{id}/status', [AdminController::class, 'updateStatusPeminjaman'])
            ->name('peminjaman.updateStatus');

        // Menghapus peminjaman
        Route::delete('/peminjaman/{id}', [AdminController::class, 'destroyPeminjaman'])
            ->name('peminjaman.destroy');


        /*
        |--------------------------------------------------------------------------
        | PENGEMBALIAN
        |--------------------------------------------------------------------------
        */

        // Daftar pengembalian
        Route::get('/pengembalian', [PengembalianController::class, 'index'])
            ->name('pengembalian.index');

        // Form pengembalian
        Route::get('/pengembalian/create/{peminjaman_id}', [PengembalianController::class, 'create'])
            ->name('pengembalian.create');

        // Simpan pengembalian
        Route::post('/pengembalian', [PengembalianController::class, 'store'])
            ->name('pengembalian.store');

        // Form edit pengembalian
        Route::get('/pengembalian/{id}/edit', [PengembalianController::class, 'edit'])
            ->name('pengembalian.edit');

        // Update pengembalian
        Route::put('/pengembalian/{id}', [PengembalianController::class, 'update'])
            ->name('pengembalian.update');

        // Hapus pengembalian
        Route::delete('/pengembalian/{id}', [PengembalianController::class, 'destroy'])
            ->name('pengembalian.destroy');


        /*
        |--------------------------------------------------------------------------
        | LOG AKTIVITAS
        |--------------------------------------------------------------------------
        */

        Route::get('/log-aktivitas', [AdminController::class, 'indexLogAktivitas'])
            ->name('logaktivitas.index');
    });



/*
|--------------------------------------------------------------------------
| PETUGAS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role.petugas'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | PROFILE PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [PetugasController::class, 'profile'])
            ->name('profile');

        Route::put('/profile', [PetugasController::class, 'updateProfile'])
            ->name('profile.update');

        Route::post('/profile/foto', [PetugasController::class, 'updateFoto'])
            ->name('profile.updateFoto');


        /*
        |--------------------------------------------------------------------------
        | PEMINJAMAN PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get('/peminjaman', [PetugasController::class, 'indexPeminjaman'])
            ->name('peminjaman.index');

        Route::post('/peminjaman/{id}/setujui', [PetugasController::class, 'setujuiPeminjaman'])
            ->name('peminjaman.setujui');

        Route::post('/peminjaman/{id}/tolak', [PetugasController::class, 'tolakPeminjaman'])
            ->name('peminjaman.tolak');


        /*
        |--------------------------------------------------------------------------
        | PENGEMBALIAN PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get('/pengembalian', [PetugasController::class, 'indexPengembalian'])
            ->name('pengembalian.index');

        Route::post('/pengembalian/{id}', [PetugasController::class, 'prosesPengembalian'])
            ->name('pengembalian.proses');


        /*
        |--------------------------------------------------------------------------
        | LAPORAN PETUGAS
        |--------------------------------------------------------------------------
        */

        Route::get('/laporan', [PetugasController::class, 'laporan'])
            ->name('laporan.index');

        Route::get('/laporan/cetak', [PetugasController::class, 'cetakLaporan'])
            ->name('laporan.cetak');
    });



/*
|--------------------------------------------------------------------------
| PEMINJAM
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role.peminjam'])
    ->prefix('peminjam')
    ->name('peminjam.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | PROFILE PEMINJAM
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [PeminjamController::class, 'profile'])
            ->name('profile');

        Route::put('/profile', [PeminjamController::class, 'updateProfile'])
            ->name('profile.update');

        Route::post('/profile/foto', [PeminjamController::class, 'updateFoto'])
            ->name('profile.updateFoto');


        /*
        |--------------------------------------------------------------------------
        | KATALOG ALAT
        |--------------------------------------------------------------------------
        */

        Route::get('/katalog', [PeminjamController::class, 'katalogAlat'])
            ->name('katalog');


        /*
        |--------------------------------------------------------------------------
        | PENGAJUAN PEMINJAMAN
        |--------------------------------------------------------------------------
        */

        Route::post('/peminjaman/ajukan', [PeminjamController::class, 'ajukanPeminjaman'])
            ->name('ajukan');


        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PEMINJAMAN
        |--------------------------------------------------------------------------
        */

        Route::get('/riwayat', [PeminjamController::class, 'riwayatPeminjaman'])
            ->name('riwayat');
    });



/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Halaman login
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');

    // Proses login
    Route::post('/login', [AuthController::class, 'login']);
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');