<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\ProgrammingController;
use App\Http\Controllers\generalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClassScheduleController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // 1. Dashboard: Acceso para todos los roles definidos
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard')->middleware('role:Admin|Profesor|Padre|Deportista');

    // 2. Módulo de Estudiantes (Solo Admin y Profesor)
    Route::middleware(['role:Admin|Profesor'])->group(function () {
        Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
        Route::get('/students/create', [StudentsController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentsController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentsController::class, 'edit'])->name('students.edit');
        Route::patch('/students/{student}', [StudentsController::class, 'update'])->name('students.update');
        Route::get('/students/{student}', [StudentsController::class, 'show'])->name('students.show');
        Route::get('/imprimir/{id}', [StudentsController::class, 'imprimir'])->name('imprimir');
        Route::get('/export', [StudentsController::class, 'export'])->name('export');
    });

        // Rutas de Mensualidades y Asistencias (Lectura: Admin, Profesor, Padre, Deportista)
        Route::middleware(['role:Admin|Profesor|Padre|Deportista'])->group(function () {
            Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::get('payments/student/{student}', [PaymentController::class, 'show'])->name('payments.show');
            
            // Visualización de Horarios para todos
            Route::get('schedules', [ClassScheduleController::class, 'index'])->name('schedules.index');
        });

        // Rutas de Asistencia (Solo Profesores y Admin para tomar lista)
        Route::middleware(['role:Admin|Profesor'])->group(function () {
            Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
            Route::get('attendances/class/{schedule}', [AttendanceController::class, 'show'])->name('attendances.show');
            Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        });

        // Rutas de Gestión (Solo Admin)
        Route::middleware(['role:Admin'])->group(function () {
            Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
            Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
            Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
            
            // Rutas de Horarios (Solo Admin para crear/eliminar)
            Route::post('schedules', [ClassScheduleController::class, 'store'])->name('schedules.store');
            Route::delete('schedules/{classSchedule}', [ClassScheduleController::class, 'destroy'])->name('schedules.destroy');
            
            Route::resource('users', UserController::class);
            Route::resource('locations', LocationController::class)->only(['index', 'store', 'update', 'destroy']);
            Route::delete('/students/{student}', [StudentsController::class, 'destroy'])->name('students.destroy');
            Route::delete('/programming/{programming}', [ProgrammingController::class, 'destroy'])->name('programming.destroy');
        });

    // 3. Módulo de Programación
    // Lectura: Para todos
    Route::get('/programming', [ProgrammingController::class, 'index'])->name('programming.index')->middleware('role:Admin|Profesor|Padre|Deportista');
    Route::get('/programming/{programming}', [ProgrammingController::class, 'show'])->name('programming.show')->middleware('role:Admin|Profesor|Padre|Deportista');

    // Escritura (Crear/Editar): Admin y Profesor
    Route::middleware(['role:Admin|Profesor'])->group(function () {
        Route::get('/programming/create', [ProgrammingController::class, 'create'])->name('programming.create');
        Route::post('/programming', [ProgrammingController::class, 'store'])->name('programming.store');
        Route::get('/programming/{programming}/edit', [ProgrammingController::class, 'edit'])->name('programming.edit');
        Route::patch('/programming/{programming}', [ProgrammingController::class, 'update'])->name('programming.update');
    });

    // 4. Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/index', [generalController::class, 'index'])->name('index');
Route::get('/Programming', [generalController::class, 'Programming'])->name('Programming');
Route::get('/Announcements', [generalController::class, 'Announcements'])->name('Announcements');

require __DIR__.'/auth.php';
