<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\Core\Access\Users\UsersController;


/*
|--------------------------------------------------------------------------
| Custom Module API Routes
|--------------------------------------------------------------------------
|
| Routes in this file are not automatically wrapped in the module-wide
| auth:api middleware group. Apply the required middleware explicitly.
|
*/

Route::prefix('core')->group(function (): void {
    Route::prefix('access')->group(function (): void {

        Route::post('users/login', [UsersController::class, 'login']);
        Route::post('users/forgot-password', [UsersController::class, 'forgotPassword']);

        Route::post('users/reset-password', [UsersController::class, 'resetPassword']);
    });
});
