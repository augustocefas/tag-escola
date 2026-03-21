<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/get/tenant/id', function () {
        return tenant('id');
    })->name('tenant.id');
    Route::get('', function () {
        return response()->file(public_path('spa/index.html'));
    })->where('any', '.*');
    Route::get('/{any?}', function () {
        return response()->file(public_path('spa/index.html'));
    })->where('any', '.*');
});
