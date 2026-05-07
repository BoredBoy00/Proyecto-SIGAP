<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AuthController;
use App\Models\Patient;

// Rutas de Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirección de la raíz
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->rol === 'admin' 
            ? redirect()->route('users.index') 
            : redirect()->route('patients.create'); // Corregido de 'pacients' a 'patients'
    }
    return redirect()->route('login');
});

// Rutas Protegidas (
Route::middleware('auth')->group(function () {

    Route::get('/auditoria', function () {
        return view('audit');
    })->name('audit');

    Route::prefix('pacientes')->name('patients.')->group(function () {
        Route::get('/registro-individual', [PatientController::class, 'create'])->name('create');
        Route::post('/registro-individual', [PatientController::class, 'store'])->name('store');

        Route::get('/ver', [PatientController::class, 'look'])->name('look');

    });

Route::resource('patients', PatientController::class);

    // Grupo de Exámenes
    Route::prefix('examenes')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
    });

    // Grupo de Usuarios (Solo Admin)
    Route::prefix('usuarios')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/nuevo', [UserController::class, 'create'])->name('create');
        Route::post('/nuevo', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

}); 