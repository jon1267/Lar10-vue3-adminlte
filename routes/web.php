<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\AppointmentStatusController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardStatController;

//Route::get('/admin/dashboard', function () {
//    return view('dashboard');
//});

Route::get('/', function () {
    return view('welcome');
});

//Route::get('csrf', function () { return csrf_token(); }); // for postman

Route::middleware('auth')->group(function () {

    Route::get('/api/stats/appointments', [DashboardStatController::class, 'appointments']);
    Route::get('/api/users', [UserController::class, 'index']);
    Route::post('/api/users', [UserController::class, 'store']);
    //Route::get('/api/users/search', [UserController::class, 'search']);
    Route::patch('/api/users/{user}/change-role',[UserController::class, 'changeRole']);
    Route::put('/api/users/{user}', [UserController::class, 'update']);
    Route::delete('/api/users/{user}', [UserController::class, 'destroy']);
    Route::delete('/api/users', [UserController::class, 'bulkDelete']);

    Route::get('/api/clients', [ClientController::class, 'index']);

    Route::get('/api/appointment-status',[AppointmentStatusController::class, 'getStatusWithCount']);
    Route::get('/api/appointments', [AppointmentController::class, 'index']);
    Route::post('/api/appointments/create', [AppointmentController::class, 'store']);
    Route::get('/api/appointments/{appointment}/edit', [AppointmentController::class, 'edit']);
    Route::put('/api/appointments/{appointment}/update', [AppointmentController::class, 'update']);

    //sheet!!! was error: Method delete not allowed, GET|HEAD method allowed. (? match exists get route ???)
    //route was: '/api/appointments/{appointment}' only when change route string to this, IT FIXED ...
    Route::delete('/api/delete-appointment/{appointment}', [AppointmentController::class, 'destroy']);


});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')
    ->middleware('auth');
