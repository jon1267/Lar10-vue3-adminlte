<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/admin/dashboard', function () {
//    return view('dashboard');
//});

//Route::get('csrf', function () { return csrf_token(); }); // for postman

Route::get('/api/users', [UserController::class, 'index']);
Route::post('/api/users', [UserController::class, 'store']);
Route::put('/api/users/{user}', [UserController::class, 'update']);
Route::delete('/api/users/{user}', [UserController::class, 'destroy']);

Route::get('{view}', ApplicationController::class)->where('view', '(.*)');
