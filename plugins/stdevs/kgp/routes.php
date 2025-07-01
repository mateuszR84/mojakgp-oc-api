<?php

use StDevs\Kgp\Middleware\JWTAuth;
use StDevs\Kgp\Api\Controllers\AuthController;
use StDevs\Kgp\Api\Controllers\UserController;
use StDevs\Kgp\Api\Controllers\HikesController;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;

// Route::group(['middleware' => AuthenticateWithBasicAuth::class], function() {
//     Route::post('api/registration', [UserController::class, 'register']);
// });

Route::post('api/registration', [UserController::class, 'register']);
Route::post('api/login', [AuthController::class, 'login']);
Route::post('api/logout', [AuthController::class, 'logout']);

//user actions
Route::post('api/user/avatar', [UserController::class, 'updateAvatar']);

//hikes
Route::post('api/add-hike', [HikesController::class, 'create'])->middleware(JWTAuth::class);

Route::get('/health', function() {
    return response()->json(['status' => 'ok', 'timestamp' => now()]);
});

