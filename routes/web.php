<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('schedules', \App\Http\Controllers\Admin\ScheduleController::class);
    Route::resource('medicines', \App\Http\Controllers\Admin\MedicineController::class);
    Route::resource('bills', \App\Http\Controllers\Admin\BillController::class);
    Route::post('bills/{bill}/pay', [\App\Http\Controllers\Admin\BillController::class, 'pay'])->name('bills.pay');
    Route::get('logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs.index');
});

Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::resource('appointments', \App\Http\Controllers\Patient\AppointmentController::class);
    Route::post('appointments/{appointment}/cancel', [\App\Http\Controllers\Patient\AppointmentController::class, 'cancel'])->name('appointments.cancel');
});

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('records/history', [\App\Http\Controllers\Doctor\MedicalRecordController::class, 'history'])->name('records.history');
    Route::get('records', [\App\Http\Controllers\Doctor\MedicalRecordController::class, 'index'])->name('records.index');
    Route::get('records/{registration}/create', [\App\Http\Controllers\Doctor\MedicalRecordController::class, 'create'])->name('records.create');
    Route::post('records/{registration}', [\App\Http\Controllers\Doctor\MedicalRecordController::class, 'store'])->name('records.store');
});



require __DIR__.'/auth.php';
