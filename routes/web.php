<?php

use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/home', '/');
Route::get('/audit', [AuditLogController::class, 'index'])->name('audit.index');

//Sin autenticar usuario
Route::middleware(['guest'])->group(function () {

    Route::get('/', fn() => redirect()->route('login'));

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

//Funciona si estoy autenticado
Route::middleware(['auth'])->group(function () {

    //Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Calendario de reservas
    Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');

    //Reserva de usuario
    Route::get('/reservations/user', [ReservationController::class, 'myReservations'])->name('reservations.user');

    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->name('reservations.destroy');

    //Incidencias
    Route::resource('incidences', IncidenceController::class)->only([
        'index', 'create', 'store', 'show'
    ]);

    // Rutas de administradores
    Route::middleware('admin')->group(function () {

        // CRUD para Admin
        Route::resource('resources', ResourceController::class);

        // CRUD para Admin
        Route::resource('incidences', IncidenceController::class)->only([
            'edit', 'update', 'destroy'
        ]);
    });
});
