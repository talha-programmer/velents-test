<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('users', [UserController::class, 'index']);

Route::controller(UserController::class)->middleware('auth:api')->prefix('users')->group(function() {
    Route::post('/store', 'store');
    Route::post('/update/{user}', 'update');
    Route::delete('/destroy/{user}', 'destroy');

});