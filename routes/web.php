<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\Admin\AppointmentController;
// use App\Http\Controllers\Admin\AppointmentStatusController;
// use App\Http\Controllers\Admin\ClientController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// });

// Route::get('/api/users', [UserController::class, 'index']);
// Route::post('/api/users', [UserController::class, 'store']);
// Route::patch('/api/users/{user}/change-role', [UserController::class, 'changeRole']);
// Route::put('/api/users/{user}', [UserController::class, 'update']);
// Route::delete('/api/users/{user}', [UserController::class, 'destory']);
// Route::delete('/api/users', [UserController::class, 'bulkDelete']);

// Route::get('/api/clients', [ClientController::class, 'index']);

// Route::get('/api/appointment-status', [AppointmentStatusController::class, 'getStatusWithCount']);
// Route::get('/api/appointments', [AppointmentController::class, 'index']);
// Route::post('/api/appointments/create', [AppointmentController::class, 'store']);
// Route::get('/api/appointments/{appointment}/edit', [AppointmentController::class, 'edit']);
// Route::put('/api/appointments/{appointment}/edit', [AppointmentController::class, 'update']);
// Route::delete('/api/appointments/{appointment}', [AppointmentController::class, 'destroy']);

Route::get('{view}',ApplicationController::class)->where('view','.*');

// Route::get('login', [ApplicationController::class, 'loginview']);

// Route::get('/admin/login', function () {
//     return view('admin/auth/login');
// });
