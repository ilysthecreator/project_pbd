<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\SatuanController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\PengadaanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\PenerimaanController;
use App\Http\Controllers\Admin\KartuStokController;
use App\Http\Controllers\Admin\ReturPenjualanController;
use App\Http\Controllers\Admin\MarginPenjualanController;
use App\Http\Controllers\AuthController;

// --- PERBAIKAN DI SINI ---
// Ganti 'login.view' menjadi 'login' agar sesuai dengan redirect di DashboardController
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login'); 

Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Route Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Resource Routes
    Route::resource('barang', BarangController::class);
    Route::resource('satuan', SatuanController::class);
    Route::resource('vendor', VendorController::class);
    Route::resource('pengadaan', PengadaanController::class);
    Route::resource('user', UserController::class);
    
    // Custom Routes
    Route::post('pengadaan/{id}/status', [PengadaanController::class, 'updateStatus'])->name('pengadaan.updateStatus');
    Route::resource('penjualan', PenjualanController::class);
    Route::resource('penerimaan', PenerimaanController::class)->only(['index', 'create', 'store', 'show']);
    
    // API Routes
    Route::get('/api/pengadaan/{id}/details', [PenerimaanController::class, 'getPengadaanDetails'])->name('api.pengadaan.details');
    Route::get('/retur-penjualan/get-details/{id}', [ReturPenjualanController::class, 'getPenjualanDetails'])->name('api.penjualan.details');
    
    Route::resource('/retur-penjualan', ReturPenjualanController::class)->names('retur_penjualan'); 
    Route::get('kartu-stok', [KartuStokController::class, 'index'])->name('kartustok.index');
    Route::resource('margin-penjualan', MarginPenjualanController::class)->only(['index'])->names('margin_penjualan');
});