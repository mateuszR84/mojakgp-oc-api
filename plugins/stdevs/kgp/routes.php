<?php

use StDevs\Kgp\Api\Controllers\AuthController;
use StDevs\Kgp\Api\Controllers\UserController;

// Route::group(['middleware' => AuthenticateWithBasicAuth::class], function() {
//     Route::post('api/registration', [UserController::class, 'register']);
// });

Route::post('api/registration', [UserController::class, 'register']);
Route::post('api/login', [AuthController::class, 'login']);
Route::post('api/logout', [AuthController::class, 'logout']);

Route::get('/health', function() {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

