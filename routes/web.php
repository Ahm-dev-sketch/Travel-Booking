<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminController;


// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Kelola Jadwal
    Route::get('/jadwals', [AdminController::class, 'jadwals'])->name('jadwals');
    Route::get('/jadwals/create', [AdminController::class, 'createJadwal'])->name('jadwals.create');
    Route::post('/jadwals', [AdminController::class, 'storeJadwal'])->name('jadwals.store');
    Route::get('/jadwals/{jadwal}/edit', [AdminController::class, 'editJadwal'])->name('jadwals.edit');
    Route::put('/jadwals/{jadwal}', [AdminController::class, 'updateJadwal'])->name('jadwals.update');
    Route::delete('/jadwals/{jadwal}', [AdminController::class, 'destroyJadwal'])->name('jadwals.destroy');

    // Kelola Booking
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::put('/bookings/{booking}', [AdminController::class, 'updateBooking'])->name('bookings.update');

    // AJAX untuk kursi realtime
    Route::get('/jadwal/{id}/seats', [BookingController::class, 'getSeats'])->name('jadwal.seats');


    // Kelola Pelanggan
    Route::get('/pelanggan', [AdminController::class, 'pelanggan'])->name('pelanggan');
    Route::get('/pelanggan/create', [AdminController::class, 'createPelanggan'])->name('pelanggan.create');
    Route::post('/pelanggan', [AdminController::class, 'storePelanggan'])->name('pelanggan.store');
    Route::get('/pelanggan/{customer}/edit', [AdminController::class, 'editPelanggan'])->name('pelanggan.edit');
    Route::put('/pelanggan/{customer}', [AdminController::class, 'updatePelanggan'])->name('pelanggan.update');
    Route::delete('/pelanggan/{customer}', [AdminController::class, 'destroyPelanggan'])->name('pelanggan.destroy');

    // Laporan Pendapatan (tambahan fix)
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
});

// ==================== USER ROUTES ====================
Route::get('/', function () {
    $user = Auth::user();
    return view('user.home', ['firstName' => $user ? $user->name : '']);
})->name('home');

// Public pages
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/register/verify', [AuthController::class, 'showRegisterVerify'])->name('register.verify');
Route::post('/register/verify', [AuthController::class, 'verifyRegisterOtp'])->name('register.verify.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot & Reset Password
Route::get('password/reset', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('password/reset/confirm', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('password/reset/confirm', [AuthController::class, 'resetPassword'])->name('password.update');

// Protected pages
Route::middleware('auth')->group(function () {
    Route::get('/pesan-tiket', [BookingController::class, 'create'])->name('pesan');
    Route::get('/riwayat', [BookingController::class, 'index'])->name('riwayat');
    Route::post('/pesan-tiket', [BookingController::class, 'store'])->name('booking.store');

    // Added route for fetching booked seats dynamically
    Route::get('/jadwal/{jadwal}/seats', [JadwalController::class, 'getBookedSeats']);
});
