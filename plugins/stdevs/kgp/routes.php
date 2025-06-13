<?php

use StDevs\Kgp\Api\Controllers\AuthController;
use StDevs\Kgp\Api\Controllers\UserController;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;

// Route::group(['middleware' => AuthenticateWithBasicAuth::class], function() {
//     Route::post('api/registration', [UserController::class, 'register']);
// });

Route::post('api/registration', [UserController::class, 'register']);
Route::post('api/login', [AuthController::class, 'login']);
Route::post('api/logout', [AuthController::class, 'logout']);

