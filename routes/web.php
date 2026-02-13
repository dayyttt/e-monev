<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\SubKegiatanController;
use App\Http\Controllers\RincianKegiatanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Bidang Routes
Route::prefix('bidang')->name('bidang.')->group(function () {
    Route::get('/', [App\Http\Controllers\BidangController::class, 'index'])->name('index');
    Route::get('/{bidang}', [App\Http\Controllers\BidangController::class, 'show'])->name('show');
    Route::post('/', [App\Http\Controllers\BidangController::class, 'store'])->name('store');
    Route::put('/{bidang}', [App\Http\Controllers\BidangController::class, 'update'])->name('update');
    Route::delete('/{bidang}', [App\Http\Controllers\BidangController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [App\Http\Controllers\BidangController::class, 'reorder'])->name('reorder');
});

// Kegiatan Routes
Route::prefix('kegiatan')->name('kegiatan.')->group(function () {
    Route::get('/', [KegiatanController::class, 'index'])->name('index');
    Route::post('/', [KegiatanController::class, 'store'])->name('store');
    Route::put('/{kegiatan}', [KegiatanController::class, 'update'])->name('update');
    Route::delete('/{kegiatan}', [KegiatanController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [KegiatanController::class, 'reorder'])->name('reorder');
    Route::post('/redistribute-anggaran', [KegiatanController::class, 'redistributeAnggaran'])->name('redistribute-anggaran');
});

// Sub Kegiatan Routes
Route::prefix('sub-kegiatan')->name('sub-kegiatan.')->group(function () {
    Route::post('/', [SubKegiatanController::class, 'store'])->name('store');
    Route::put('/{subKegiatan}', [SubKegiatanController::class, 'update'])->name('update');
    Route::delete('/{subKegiatan}', [SubKegiatanController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [SubKegiatanController::class, 'reorder'])->name('reorder');
});

// Rincian Kegiatan Routes
Route::prefix('rincian-kegiatan')->name('rincian-kegiatan.')->group(function () {
    Route::post('/', [RincianKegiatanController::class, 'store'])->name('store');
    Route::put('/{rincianKegiatan}', [RincianKegiatanController::class, 'update'])->name('update');
    Route::delete('/{rincianKegiatan}', [RincianKegiatanController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [RincianKegiatanController::class, 'reorder'])->name('reorder');
});

// Placeholder routes for menu items
Route::view('/monitoring', 'monitoring.index')->name('monitoring.index');
Route::view('/anggaran', 'anggaran.index')->name('anggaran.index');
Route::view('/laporan/progress', 'laporan.progress')->name('laporan.progress');
Route::view('/laporan/anggaran', 'laporan.anggaran')->name('laporan.anggaran');
Route::view('/pengaturan', 'pengaturan')->name('pengaturan');

// Realisasi Routes
Route::prefix('realisasi')->name('realisasi.')->group(function () {
    Route::get('/', [App\Http\Controllers\RealisasiController::class, 'index'])->name('index');
    Route::get('/input', [App\Http\Controllers\RealisasiController::class, 'input'])->name('input');
    Route::post('/', [App\Http\Controllers\RealisasiController::class, 'store'])->name('store');
    Route::get('/laporan/bulanan', [App\Http\Controllers\RealisasiController::class, 'laporanBulanan'])->name('laporan.bulanan');
    Route::get('/laporan/triwulanan', [App\Http\Controllers\RealisasiController::class, 'laporanTriwulanan'])->name('laporan.triwulanan');
    Route::get('/laporan/tahunan', [App\Http\Controllers\RealisasiController::class, 'laporanTahunan'])->name('laporan.tahunan');
});

// Laporan Routes
Route::prefix('laporan')->name('laporan.')->group(function () {
    Route::get('/rekap-bidang', [App\Http\Controllers\LaporanController::class, 'rekapBidang'])->name('rekap-bidang');
    Route::post('/rekap-bidang/recalculate', [App\Http\Controllers\LaporanController::class, 'recalculate'])->name('rekap-bidang.recalculate');
});
