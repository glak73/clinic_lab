<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PDFController;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/pdf/appointments', [PDFController::class, 'generateAppointmentsPDF'])
        ->name('pdf.appointments');
});
Route::middleware(['auth'])->group(function () {
    Route::post('/doctors/{id}/comments', [DoctorController::class, 'storeComment'])
        ->name('doctors.comments.store');
});
Route::group(['middleware' => 'web'], function () {
    // Маршруты для работы с врачами
    Route::get('/doctors', [DoctorController::class, 'index'])
        ->name('doctors.index');

    Route::get('/doctors/{id}', [DoctorController::class, 'show'])
        ->name('doctors.show');

    Route::get('/doctors/{id}/available-times', [DoctorController::class, 'availableTimes'])
        ->name('doctors.available-times');

    Route::get('/doctors/specialties', [DoctorController::class, 'getSpecialties'])
        ->name('doctors.specialties');

    // Маршруты для записи на прием
    Route::get('/appointments', [AppointmentController::class, 'create'])
        ->name('appointments.create');

    Route::post('/appointments', [AppointmentController::class, 'store'])
        ->name('appointments.store');

    Route::get('/appointments/success', [AppointmentController::class, 'success'])
        ->name('appointments.success');

    Route::get('/appointments/cancel/{id}', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');
});

// Защищенные маршруты для авторизованных пользователей
Route::middleware(['auth'])->group(function () {
    Route::put('/appointments/{id}', [AppointmentController::class, 'update'])
        ->name('appointments.update');

    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy'])
        ->name('appointments.destroy');
});
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
