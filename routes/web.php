<?php
use App\Http\Controllers\{AuthController, DashboardController, LaporanController, MejaController,
    MenuController, PelangganController, ReservasiAdminController, UserController};
use Illuminate\Support\Facades\Route;

// ===== Pelanggan (tanpa login) =====
Route::get('/', [PelangganController::class, 'home'])->name('home');
Route::get('/menu', [PelangganController::class, 'daftarMenu'])->name('menu.publik');
Route::get('/reservasi', [PelangganController::class, 'form'])->name('form');
Route::get('/cek', [PelangganController::class, 'cek'])->name('cek');
Route::post('/pilih-meja', [PelangganController::class, 'pilih'])->name('pilih');
Route::post('/pilih-menu', [PelangganController::class, 'pilihMenu'])->name('pilih.menu');
Route::post('/reservasi', [PelangganController::class, 'store'])->name('reservasi.store');
Route::get('/reservasi/{kode}/bayar', [PelangganController::class, 'bayarForm'])->name('bayar');
Route::post('/reservasi/{kode}/bayar', [PelangganController::class, 'bayar'])->name('bayar.proses');
Route::get('/status/{kode}', [PelangganController::class, 'status'])->name('status');

// ===== Login =====
Route::get('/login', [AuthController::class, 'form'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== Pemilik + Staf =====
Route::middleware(['auth', 'role:pemilik,staf'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => auth()->user()->isPemilik()
        ? redirect()->route('admin.dashboard') : redirect()->route('admin.reservasi'))->name('home');
    Route::get('/reservasi', [ReservasiAdminController::class, 'index'])->name('reservasi');
    Route::get('/reservasi/baru', [ReservasiAdminController::class, 'baru'])->name('reservasi.baru');
    Route::post('/reservasi', [ReservasiAdminController::class, 'simpan'])->name('reservasi.simpan');
    Route::patch('/reservasi/{reservasi}/status', [ReservasiAdminController::class, 'status'])->name('reservasi.status');
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');
    Route::patch('/menu/{menu}/stok', [MenuController::class, 'stok'])->name('menu.stok');
    Route::patch('/menu/{menu}/soldout', [MenuController::class, 'soldOut'])->name('menu.soldout');
});

// ===== Khusus Pemilik =====
Route::middleware(['auth', 'role:pemilik'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::put('/menu/{menu}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{menu}', [MenuController::class, 'destroy'])->name('menu.destroy');
    Route::get('/meja', [MejaController::class, 'index'])->name('meja');
    Route::post('/meja', [MejaController::class, 'store'])->name('meja.store');
    Route::put('/meja/{meja}', [MejaController::class, 'update'])->name('meja.update');
    Route::delete('/meja/{meja}', [MejaController::class, 'destroy'])->name('meja.destroy');
    Route::get('/pengguna', [UserController::class, 'index'])->name('pengguna');
    Route::post('/pengguna', [UserController::class, 'store'])->name('pengguna.store');
    Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{user}', [UserController::class, 'destroy'])->name('pengguna.destroy');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/csv', [LaporanController::class, 'csv'])->name('laporan.csv');
});
