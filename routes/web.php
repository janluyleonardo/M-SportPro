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
use App\Http\Controllers\SuperAdmin\ClubController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de Super Admin
Route::middleware(['auth', 'verified', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::post('clubs', [ClubController::class, 'store'])->name('clubs.store');
    Route::put('clubs/{club}', [ClubController::class, 'update'])->name('clubs.update');
    Route::delete('clubs/{club}', [ClubController::class, 'destroy'])->name('clubs.destroy');
    Route::post('clubs/{club}/toggle-module', [ClubController::class, 'toggleModule'])->name('clubs.toggleModule');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas de cambio de clave obligatoria (Fuera del middleware restrictivo para evitar bucles)
    Route::get('/password/change', [PasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.update.mandatory');

    Route::middleware(['must.change.password'])->group(function () {
        // 1. Dashboard: Acceso para todos los roles definidos
        Route::get('/dashboard', function () {
            if (auth()->user()->is_super_admin) {
                $clubs = \App\Models\Club::with(['users.roles', 'modules'])->withCount('users')->get();
                return view('dashboard', compact('clubs'));
            }

            if (!auth()->user()->hasAnyRole(['Admin', 'SubAdmin', 'Profesor', 'Padre', 'Deportista'])) {
                abort(403, 'Acción no autorizada.');
            }

            return view('dashboard');
        })->name('dashboard');

        // 2. Módulo de Estudiantes (Solo Admin y Profesor)
        Route::middleware(['role:Admin|SubAdmin|Profesor'])->group(function () {
            Route::resource('students', StudentsController::class)->except(['destroy']);
            Route::get('/imprimir/{id}', [StudentsController::class, 'imprimir'])->name('imprimir');
        });

        // Rutas de Mensualidades y Asistencias
        Route::middleware(['role:Admin|SubAdmin|Profesor|Padre|Deportista'])->group(function () {
            Route::middleware(['module:financial'])->group(function () {
                Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
                Route::get('payments/student/{student}', [PaymentController::class, 'show'])->name('payments.show');
            });

            // Visualización de Horarios para todos
            Route::middleware(['module:classes'])->group(function () {
                Route::get('schedules', [ClassScheduleController::class, 'index'])->name('schedules.index');
            });
        });

        // Rutas de Asistencia
        Route::middleware(['role:Admin|SubAdmin|Profesor', 'module:classes'])->group(function () {
            Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
            Route::get('attendances/class/{schedule}', [AttendanceController::class, 'show'])->name('attendances.show');
            Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        });

        // Override de asistencia — crear: Admin|SubAdmin / revocar: solo Admin
        Route::middleware(['role:Admin|SubAdmin', 'module:classes'])->group(function () {
            Route::post('attendances/override', [AttendanceController::class, 'storeOverride'])->name('attendances.override.store');
        });
        Route::middleware(['role:Admin', 'module:classes'])->group(function () {
            Route::delete('attendances/override/{override}', [AttendanceController::class, 'destroyOverride'])->name('attendances.override.destroy');
        });


        // ── Rutas de Gestión: Admin y SubAdmin (crear / editar) ────────────────
        Route::middleware(['role:Admin|SubAdmin'])->group(function () {
            Route::middleware(['module:financial'])->group(function () {
                Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
                Route::put('payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
                Route::post('payments/{payment}/verify', [PaymentController::class, 'verifyVoucher'])->name('payments.verify');
                Route::post('payments/{payment}/reject', [PaymentController::class, 'rejectVoucher'])->name('payments.reject');
            });

            // Horarios: crear y editar
            Route::middleware(['module:classes'])->group(function () {
                Route::post('schedules', [ClassScheduleController::class, 'store'])->name('schedules.store');
                Route::put('schedules/{classSchedule}', [ClassScheduleController::class, 'update'])->name('schedules.update');
            });

            Route::resource('users', UserController::class)->except(['destroy']);
            Route::resource('locations', LocationController::class)->only(['index', 'store', 'update']);
            Route::get('/export', [StudentsController::class, 'export'])->name('export');
            Route::get('/export-template', [StudentsController::class, 'exportTemplate'])->name('export.template');
            Route::post('/import', [StudentsController::class, 'import'])->name('import');
        });

        // ── Rutas exclusivas de Admin (SOLO ELIMINAR) ────────────────────────────
        Route::middleware(['role:Admin'])->group(function () {
            Route::middleware(['module:financial'])->group(function () {
                Route::delete('payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
            });

            // Horarios: eliminar
            Route::middleware(['module:classes'])->group(function () {
                Route::delete('schedules/{classSchedule}', [ClassScheduleController::class, 'destroy'])->name('schedules.destroy');
            });

            Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
            Route::delete('locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
            Route::delete('/students/{student}', [StudentsController::class, 'destroy'])->name('students.destroy');
            Route::delete('/programming/{programming}', [ProgrammingController::class, 'destroy'])->name('programming.destroy');
        });

        // Ruta para que padres/deportistas suban comprobantes
        Route::middleware(['role:Admin|SubAdmin|Profesor|Padre|Deportista'])->group(function () {
            Route::post('payments/upload-voucher', [PaymentController::class, 'uploadVoucher'])->name('payments.upload_voucher');
        });

        // ── Tesorería e Inventario: Admin y SubAdmin (crear / editar / ver) ─────
        Route::middleware(['role:Admin|SubAdmin'])->group(function () {
            Route::middleware(['module:financial'])->group(function () {
                Route::get('products/template', [ProductController::class, 'downloadTemplate'])->name('products.template');
                Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
                Route::resource('products', ProductController::class)->except(['destroy']);

                // Tesorería
                Route::prefix('treasury')->name('treasury.')->group(function () {
                    Route::get('/', [TreasuryController::class, 'index'])->name('index');
                    Route::post('/', [TreasuryController::class, 'store'])->name('store');
                    Route::get('/salaries', [TreasuryController::class, 'salaries'])->name('salaries');
                    Route::post('/salaries/pay', [TreasuryController::class, 'payTeacher'])->name('pay_teacher');
                    Route::get('/teacher-history/{teacher}', [TreasuryController::class, 'teacherHistory'])->name('teacher_history');
                    Route::post('/settings', [TreasuryController::class, 'updateSettings'])->name('settings.update');
                });
            });
        });

        // Productos: eliminar (solo Admin)
        Route::middleware(['role:Admin', 'module:financial'])->group(function () {
            Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        });

        // 3. Módulo de Programación
        Route::middleware(['module:tournaments'])->group(function () {
            // Lectura: Para todos (incluyendo SubAdmin)
            Route::resource('programming', ProgrammingController::class)->only(['index', 'show'])->middleware('role:Admin|SubAdmin|Profesor|Padre|Deportista');

            Route::middleware(['role:Admin|SubAdmin|Profesor'])->group(function () {
                Route::resource('programming', ProgrammingController::class)->except(['index', 'show', 'destroy']);
                Route::get('/programming/imprimir/{date}', [ProgrammingController::class, 'imprimir'])->name('programming.imprimir');
                Route::get('/programming/{id}/payments', [ProgrammingController::class, 'getPayments'])->name('programming.payments.get');
                Route::post('/programming/{id}/payments', [ProgrammingController::class, 'updatePayments'])->name('programming.payments.update');
            });

            // 5. Módulo de Torneos — crear/editar: Admin|SubAdmin|Profesor
            Route::middleware(['role:Admin|SubAdmin|Profesor'])->group(function () {
                Route::resource('tournaments', TournamentController::class)->except(['destroy']);
                Route::get('/tournaments/{tournament}/payments', [TournamentController::class, 'payments'])->name('tournaments.payments');
            });
            // Torneos: eliminar — solo Admin y Profesor (mantenemos igual que antes)
            Route::middleware(['role:Admin|Profesor'])->group(function () {
                Route::delete('tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
            });
        });

        // 4. Perfil
        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('profile.edit');
            Route::patch('/profile', 'update')->name('profile.update');
            Route::put('/profile/club', 'updateClub')->name('profile.club.update');
            Route::delete('/profile', 'destroy')->name('profile.destroy');
        });
    });
});

Route::get('/index', [generalController::class, 'index'])->name('index');
Route::get('/Programming', [generalController::class, 'Programming'])->name('Programming');
Route::get('/Announcements', [generalController::class, 'Announcements'])->name('Announcements');

require __DIR__ . '/auth.php';
