<?php

use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncidenceController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/reservations', function () {
//     return "Construction";
// })->name('reservation.index');
Route::redirect('/home', '/');

//Sin autenticar usuario
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/', function () {
        return redirect()->route('login');
    });
});

//Funciona si estoy autenticado
Route::middleware(['auth'])->group(function () {

    //Calendario de reservas
    Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');

    //Recursos
    Route::resource('resources', ResourceController::class);

    //Incidencias
    Route::resource('incidences', IncidenceController::class);

    //Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    //x
});
