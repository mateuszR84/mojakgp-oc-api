<?php 

use StDevs\Kgp\Api\Controllers\UserController;

Route::group(['middleware' => BasicAuthMiddleware::class], function() {
    Route::post('api/registration', [UserController::class, 'register']);
});