<?php

use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\ResourceController;
use App\Models\Reservation;
use GuzzleHttp\Middleware;
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

Route::get('/reservations', function () {
    return "Construction";
})->name('reservation.index');

//Route::middleware(['auth'])->group(function () {
    Route::resource('resources', ResourceController::class);
    Route::resource('incidences', IncidenceController::class);
//});
