<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AhpController;
use App\Http\Controllers\WargaController;
use App\Http\Controllers\SawController;
use App\Http\Controllers\HasilController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SimulasiController;
use App\Http\Controllers\ImportController;

// Guest routes
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'form'])->name('login');
// Rate-limited: max 5 percobaan per 1 menit per IP
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Auth-protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Kriteria
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria.index');
    Route::get('/kriteria/tambah', [KriteriaController::class, 'create'])->name('kriteria.create');
    Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
    Route::get('/kriteria/{kriteria}/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
    Route::put('/kriteria/{kriteria}', [KriteriaController::class, 'update'])->name('kriteria.update');
    Route::delete('/kriteria/{kriteria}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');
    Route::post('/kriteria/reset-bobot', [KriteriaController::class, 'resetBobot'])->name('kriteria.reset-bobot');

    // AHP
    Route::get('/ahp', [AhpController::class, 'index'])->name('ahp.index');
    Route::post('/ahp/simpan', [AhpController::class, 'simpan'])->name('ahp.simpan');

    // Warga — Route::resource (create=tambah, show, edit, update, destroy via Resource)
    // Catatan: 'create' di-override URL-nya agar tetap /warga/tambah (konsistensi UI)
    Route::resource('warga', WargaController::class)
        ->except(['create'])
        ->names('warga');
    Route::get('/warga/tambah', [WargaController::class, 'create'])->name('warga.create');

    // Import CSV
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::post('/import/upload', [ImportController::class, 'upload'])->name('import.upload');

    // SAW
    Route::get('/saw', [SawController::class, 'index'])->name('saw.index');
    Route::post('/saw/hitung', [SawController::class, 'hitung'])->name('saw.hitung');

    // Hasil + Export PDF
    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.index');
    Route::get('/hasil/export-pdf', [HasilController::class, 'exportPdf'])->name('hasil.export-pdf');

    // Simulasi Bobot
    Route::get('/simulasi', [SimulasiController::class, 'index'])->name('simulasi.index');
    Route::post('/simulasi/hitung', [SimulasiController::class, 'hitung'])->name('simulasi.hitung');
});
