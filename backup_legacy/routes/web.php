<?php

use App\Http\Controllers\generalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\ProgrammingController;
use App\Http\Controllers\nomineeController;
// use PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('/students', StudentsController::class);
    Route::resource('/programming', ProgrammingController::class);
    Route::resource('/nominee', nomineeController::class);
    Route::get('/imprimir/{id}', [StudentsController::class, 'imprimir'])->name('imprimir');
    // Route::put('editarRegistro/{id}', [nomineeController::class, 'update'])->name('editarRegistro');
    Route::get('/export', [StudentsController::class, 'export'])->name('export');
});

Route::get('/index', [generalController::class, 'index'])->name('index');
Route::get('/Programming', [generalController::class, 'Programming'])->name('Programming');
Route::get('/Announcements', [generalController::class, 'Announcements'])->name('Announcements');
