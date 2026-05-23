<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Patient\MedicalRecordController as PatientMedicalRecordController;
use App\Http\Controllers\Patient\BillController as PatientBillController;
use App\Http\Controllers\Doctor\MedicalRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/patient', [ProfileController::class, 'patientUpdate'])->name('profile.patient.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('schedules', ScheduleController::class);
    Route::resource('medicines', MedicineController::class);
    Route::resource('bills', BillController::class);
    Route::post('bills/{bill}/pay', [BillController::class, 'pay'])->name('bills.pay');
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');
    
    Route::get('bpjs', [\App\Http\Controllers\Admin\BpjsController::class, 'index'])->name('bpjs.index');
    Route::patch('bpjs/{patient}', [\App\Http\Controllers\Admin\BpjsController::class, 'update'])->name('bpjs.update');
});

Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::resource('appointments', AppointmentController::class);
    Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

    Route::get('records', [PatientMedicalRecordController::class, 'index'])->name('records.index');
    Route::get('records/{record}', [PatientMedicalRecordController::class, 'show'])->name('records.show');

    Route::get('bills', [PatientBillController::class, 'index'])->name('bills.index');
    Route::get('bills/{bill}', [PatientBillController::class, 'show'])->name('bills.show');
});

Route::middleware(['auth', 'role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('records/history', [MedicalRecordController::class, 'history'])->name('records.history');
    Route::get('records', [MedicalRecordController::class, 'index'])->name('records.index');
    Route::get('records/{registration}/create', [MedicalRecordController::class, 'create'])->name('records.create');
    Route::post('records/{registration}', [MedicalRecordController::class, 'store'])->name('records.store');
});



require __DIR__.'/auth.php';
