<?php 

use StDevs\Kgp\Api\Controllers\UserController;

Route::post('api/registration', [UserController::class, 'register']);