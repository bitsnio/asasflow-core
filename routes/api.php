<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\Core\Tenancy\Tenants\TenantsController;
use Modules\Core\Http\Controllers\Core\Access\Users\UsersController;
use Modules\Core\Http\Controllers\Core\Access\Roles\RolesController;
use Modules\Core\Http\Controllers\Core\Access\Permissions\PermissionsController;
use Modules\Core\Http\Controllers\Core\Configuration\Settings\SettingsController;
use Modules\Core\Http\Controllers\Core\Tenancy\TenantDomains\TenantDomainsController;
use Modules\Core\Http\Controllers\Core\Tenancy\Sites\SitesController;

Route::prefix('core')
    // ->middleware(['asasflow.permission'])
    ->group(function () {
    Route::prefix('tenancy')
        ->group(function () {
        Route::apiResource('tenants', TenantsController::class);

        Route::post('tenants/activate', [TenantsController::class, 'activate']);

        Route::post('tenants/suspend', [TenantsController::class, 'suspend']);

        Route::post('tenants/provision', [TenantsController::class, 'provision']);

        Route::apiResource('tenant-domains', TenantDomainsController::class);

        Route::post('tenant-domains/verify', [TenantDomainsController::class, 'verify']);

        Route::apiResource('sites', SitesController::class);

    });

    Route::prefix('access')
        ->group(function () {
        Route::apiResource('users', UsersController::class);

        Route::post('users/reset-password', [UsersController::class, 'resetPassword']);

        Route::post('users/activate', [UsersController::class, 'activate']);

        Route::post('users/deactivate', [UsersController::class, 'deactivate']);

        Route::post('users/login', [UsersController::class, 'login']);

        Route::post('users/logout', [UsersController::class, 'logout']);

        Route::post('users/register', [UsersController::class, 'register']);

        Route::post('users/assign-roles', [UsersController::class, 'assignRoles']);

        Route::apiResource('roles', RolesController::class);

        Route::post('roles/assign-permissions', [RolesController::class, 'assignPermissions']);

        Route::apiResource('permissions', PermissionsController::class);

    });

    Route::prefix('configuration')
        ->group(function () {
        Route::apiResource('settings', SettingsController::class);

    });

});

