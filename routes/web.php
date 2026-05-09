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
use App\Http\Controllers\TreasuryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\TournamentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas de cambio de clave obligatoria (Fuera del middleware restrictivo para evitar bucles)
    Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.update.mandatory');

    Route::middleware(['must.change.password'])->group(function () {
        // 1. Dashboard: Acceso para todos los roles definidos
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard')->middleware('role:Admin|Profesor|Padre|Deportista');

    // 2. Módulo de Estudiantes (Solo Admin y Profesor)
    Route::middleware(['role:Admin|Profesor'])->group(function () {
        Route::resource('students', StudentsController::class)->except(['destroy']);
        Route::get('/imprimir/{id}', [StudentsController::class, 'imprimir'])->name('imprimir');
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
            
            // Rutas de Horarios (Solo Admin para crear/editar/eliminar)
            Route::post('schedules', [ClassScheduleController::class, 'store'])->name('schedules.store');
            Route::put('schedules/{classSchedule}', [ClassScheduleController::class, 'update'])->name('schedules.update');
            Route::delete('schedules/{classSchedule}', [ClassScheduleController::class, 'destroy'])->name('schedules.destroy');
            
            Route::resource('users', UserController::class);
            Route::resource('locations', LocationController::class)->only(['index', 'store', 'update', 'destroy']);
            Route::delete('/students/{student}', [StudentsController::class, 'destroy'])->name('students.destroy');
            Route::delete('/programming/{programming}', [ProgrammingController::class, 'destroy'])->name('programming.destroy');
            Route::get('/export', [StudentsController::class, 'export'])->name('export');
            Route::get('/export-template', [StudentsController::class, 'exportTemplate'])->name('export.template');
            Route::post('/import', [StudentsController::class, 'import'])->name('import');
            
            // Verificación de comprobantes
            Route::post('payments/{payment}/verify', [PaymentController::class, 'verifyVoucher'])->name('payments.verify');
            Route::post('payments/{payment}/reject', [PaymentController::class, 'rejectVoucher'])->name('payments.reject');
        });

        // Ruta para que padres/deportistas suban comprobantes
        Route::middleware(['role:Admin|Profesor|Padre|Deportista'])->group(function () {
            Route::post('payments/upload-voucher', [PaymentController::class, 'uploadVoucher'])->name('payments.upload_voucher');
        });

        Route::middleware(['role:Admin'])->group(function () {
            // Módulo de Tesorería
            // Inventario y Productos
    Route::get('products/template', [ProductController::class, 'downloadTemplate'])->name('products.template');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
    Route::resource('products', ProductController::class);
    
    // Tesorería
    Route::prefix('treasury')->name('treasury.')->group(function() {
                Route::get('/', [TreasuryController::class, 'index'])->name('index');
                Route::post('/', [TreasuryController::class, 'store'])->name('store');
                Route::get('/salaries', [TreasuryController::class, 'salaries'])->name('salaries');
                Route::post('/salaries/pay', [TreasuryController::class, 'payTeacher'])->name('pay_teacher');
                Route::get('/teacher-history/{teacher}', [TreasuryController::class, 'teacherHistory'])->name('teacher_history');
                Route::post('/settings', [TreasuryController::class, 'updateSettings'])->name('settings.update');
            });
        });

    // 3. Módulo de Programación
    // Lectura: Para todos
    Route::resource('programming', ProgrammingController::class)->only(['index', 'show'])->middleware('role:Admin|Profesor|Padre|Deportista');

        Route::middleware(['role:Admin|Profesor'])->group(function () {
            Route::resource('programming', ProgrammingController::class)->except(['index', 'show', 'destroy']);
            Route::get('/programming/imprimir/{date}', [ProgrammingController::class, 'imprimir'])->name('programming.imprimir');
            Route::get('/programming/{id}/payments', [ProgrammingController::class, 'getPayments'])->name('programming.payments.get');
            Route::post('/programming/{id}/payments', [ProgrammingController::class, 'updatePayments'])->name('programming.payments.update');
        });

    // 5. Módulo de Torneos
    Route::middleware(['role:Admin|Profesor'])->group(function () {
        Route::resource('tournaments', TournamentController::class);
        Route::get('/tournaments/{tournament}/payments', [TournamentController::class, 'payments'])->name('tournaments.payments');
    });

    // 4. Perfil
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});
});

Route::get('/index', [generalController::class, 'index'])->name('index');
Route::get('/Programming', [generalController::class, 'Programming'])->name('Programming');
Route::get('/Announcements', [generalController::class, 'Announcements'])->name('Announcements');

require __DIR__.'/auth.php';
