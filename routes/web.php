<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:super_admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware('role:super_admin|receptionist')->group(function () {
        Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    });

    Route::middleware('role:receptionist')->group(function () {
        Route::get('/doctors/export', [DoctorController::class, 'export'])->name('doctors.export');
        Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
        Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
        Route::post('/doctors/import', [DoctorController::class, 'import'])->name('doctors.import');
    });

    Route::middleware('role:super_admin|receptionist|doctor')->group(function () {
        Route::get('/doctors/{doctorId}/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    });

    Route::middleware('role:doctor')->group(function () {
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    });
});

Route::fallback(fn() => redirect()->route('login'));
