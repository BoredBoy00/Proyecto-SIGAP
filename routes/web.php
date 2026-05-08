<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConvController;
use App\Http\Controllers\DashboardController;


// Rutas de Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Redirección de la raíz
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

// Rutas Protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/auditoria', function () {
        return view('audit');
    })->name('audit');

    Route::prefix('pacientes')->name('patients.')->group(function () {
        Route::get('/registro-individual', [PatientController::class, 'create'])->name('create');
        Route::post('/registro-individual', [PatientController::class, 'store'])->name('store');
        Route::get('/ver', [PatientController::class, 'look'])->name('look');
        Route::get('/{id}/edit', [PatientController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [PatientController::class, 'update'])->name('update');
        Route::delete('/{id}', [PatientController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('examenes')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::post('/', [ExamController::class, 'store'])->name('store');
        Route::patch('/{id}', [ExamController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExamController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('usuarios')->name('users.')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/nuevo', [UserController::class, 'create'])->name('create');
        Route::post('/nuevo', [UserController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [UserController::class, 'update'])->name('update');
        Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('convenios')->name('convens.')->group(function() {
        Route::get('/',[ConvController::class, 'index']) ->name('index');
        Route::get('/crear',[ConvController::class, 'create']) ->name('create');
        Route::post('/', [ConvController::class, 'store']) ->name('store');
        Route::get('/{id}/edit', [ConvController::class, 'edit']) ->name('edit');
        Route::patch('/{id}',[ConvController::class, 'update']) ->name ('update');
        Route::delete('/{id}', [ConvController::class, 'destroy']) ->name ('destroy');
    });
});
