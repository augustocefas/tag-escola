<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Helium\{
    AuthHeliumController,
    HeliumController,
    TenantController,
    DomainController,
    SubdomainController,
    PaisController,
    EstadoController,
    CidadeController,
};

// use App\Http\Controllers\Client\{PropriedadeController};


Route::get('/', function () {
    return '';
});

Route::get('/info', function() {
    phpinfo();
}   );


Route::prefix('/helium')->group(function () {
    Route::get('/dashboard', [HeliumController::class, 'dashboard'])->name('helium.dashboard');
    Route::prefix('auth')->group(function () {
        Route::get('/login', [AuthHeliumController::class, 'showLoginForm'])->name('helium.auth.login');
        Route::get('/logout', [AuthHeliumController::class, 'logout'])->name('helium.auth.logout');
        Route::post('/login', [AuthHeliumController::class, 'login']);
        Route::get('/dev', [AuthHeliumController::class, 'dev']);
    });
    Route::prefix('tenant')->group(function () {
        Route::get('/', [TenantController::class, 'index'])->name('helium.tenant.index');
    });
    Route::prefix('domain')->group(function () {
        Route::get('/', [DomainController::class, 'index'])->name('helium.domain.index');
        Route::post('/', [DomainController::class, 'store'])->name('helium.domain.store');
        Route::put('/{id}', [DomainController::class, 'update'])->name('helium.domain.update');
    });
    Route::prefix('subdomain')->group(function () {
        Route::get('/{domain_id}', [SubdomainController::class, 'index'])->name('helium.subdomain.index');
        Route::post('/', [SubdomainController::class, 'store'])->name('helium.subdomain.store');
        Route::put('/{id}', [SubdomainController::class, 'update'])->name('helium.subdomain.update');
        Route::delete('/{id}', [SubdomainController::class, 'destroy'])->name('helium.subdomain.destroy');
    });

    Route::prefix('pais')->group(function () {
        Route::get('/', [PaisController::class, 'index'])->name('helium.pais.index');
        Route::post('/', [PaisController::class, 'store'])->name('helium.pais.store');
        Route::put('/{id}', [PaisController::class, 'update'])->name('helium.pais.update');
        Route::delete('/{id}', [PaisController::class, 'destroy'])->name('helium.pais.destroy');
    });

    Route::prefix('estado')->group(function () {
        Route::get('/{pais_id?}', [EstadoController::class, 'index'])->name('helium.estado.index');
        Route::post('/', [EstadoController::class, 'store'])->name('helium.estado.store');
        Route::put('/{id}', [EstadoController::class, 'update'])->name('helium.estado.update');
        Route::delete('/{id}', [EstadoController::class, 'destroy'])->name('helium.estado.destroy');
    });

    Route::prefix('cidade')->group(function () {
        Route::get('/{estado_id?}', [CidadeController::class, 'index'])->name('helium.cidade.index');
        Route::post('/', [CidadeController::class, 'store'])->name('helium.cidade.store');
        Route::put('/{id}', [CidadeController::class, 'update'])->name('helium.cidade.update');
        Route::delete('/{id}', [CidadeController::class, 'destroy'])->name('helium.cidade.destroy');
    });

    Route::prefix('remote/{tenantId}')->middleware(['switch.tenant'])->group(function () {
        // Route::prefix('propriedade')->group(function () {
        //     Route::get('/', [PropriedadeController::class, 'index'])->name('helium.remote.propriedade.index');
        // });
    });

});
