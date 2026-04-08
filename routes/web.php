<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ExamController;

Route::get('/', function () {
    return redirect()->route('users.index');
});

Route::get('/auditoria', function () {
    return view('audit');
})->name('audit');

Route::prefix('pacientes')->name('patients.')->group(function () {
    Route::get('/registro-individual', [PatientController::class, 'create'])->name('create');
    Route::post('/registro-individual', [PatientController::class, 'store'])->name('store');
});

Route::prefix('examenes')->name('exams.')->group(function () {
    Route::get('/', [ExamController::class, 'index'])->name('index');
});

Route::prefix('usuarios')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/nuevo', [UserController::class, 'create'])->name('create');
    Route::post('/nuevo', [UserController::class, 'store'])->name('store');
});
