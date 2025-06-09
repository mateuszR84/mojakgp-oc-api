<?php

use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use StDevs\Kgp\Api\Controllers\UserController;

// Route::group(['middleware' => AuthenticateWithBasicAuth::class], function() {
//     Route::post('api/registration', [UserController::class, 'register']);
// });

Route::post('api/registration', [UserController::class, 'register']);