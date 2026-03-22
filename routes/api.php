<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\{
    InitializeTenancyByDomain,
    PreventAccessFromCentralDomains
};


use App\Http\Controllers\Client\{
    AuthController as AuthClientController,
    AlunoController,
    AlunoSalaController,
    AnexoController,
    ArquivoController,
    ConfigController,
    DominioController,
    SalaController,
    TagAlunoController,
    TagController,
    TpDominioController,
    UsersControllers,
    UsersSalaController
};


Route::middleware([
    //para uso por ip
    //'switch.tenant',
    //para produção por domínio ou subdomínio
    //InitializeTenancyByDomainOrSubdomain::class,
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');

    });
    Route::get('/manut/user-dev-create', [AuthClientController::class, 'devCreateUser']);
    Route::get('/manut/user-dev-delete', [AuthClientController::class, 'devDeleteUser']);

    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthClientController::class, 'login']);
        Route::get('/dev', [AuthClientController::class, 'dev']);
    });

    Route::middleware('jwt.auth')->group(function () {

        Route::prefix('auth')->group(function () {
            Route::match(['get', 'post'], 'check', [AuthClientController::class, 'isLogged']);
            Route::match(['get', 'post'], 'logout', [AuthClientController::class, 'logout']);
            Route::match(['get', 'post'], 'refresh', [AuthClientController::class, 'refresh']);
            Route::match(['get', 'post'], 'me', [AuthClientController::class, 'me']);
        });

        Route::prefix('alunos')->group(function () {
            Route::get('/', [AlunoController::class, 'index']);
            Route::post('/', [AlunoController::class, 'store']);
            Route::get('/{id}', [AlunoController::class, 'show']);
            Route::put('/{id}', [AlunoController::class, 'update']);
            Route::delete('/{id}', [AlunoController::class, 'destroy']);
        });

        Route::prefix('aluno-sala')->group(function () {
            Route::get('/', [AlunoSalaController::class, 'index']);
            Route::post('/', [AlunoSalaController::class, 'store']);
            Route::get('/{id}', [AlunoSalaController::class, 'show']);
            Route::put('/{id}', [AlunoSalaController::class, 'update']);
            Route::delete('/{id}', [AlunoSalaController::class, 'destroy']);
        });

        Route::prefix('anexos')->group(function () {
            Route::post('/', [AnexoController::class, 'store']);
        });

        Route::prefix('arquivos')->group(function () {
            Route::get('/{id}/{download?}', [ArquivoController::class, 'get']);
        });

        Route::prefix('configs')->group(function () {
            Route::get('/', [ConfigController::class, 'index']);
            Route::post('/', [ConfigController::class, 'store']);
            Route::get('/{id}', [ConfigController::class, 'getById']);
            Route::put('/{id}', [ConfigController::class, 'update']);
            Route::delete('/{id}', [ConfigController::class, 'destroy']);
        });

        Route::prefix('dominios')->group(function () {
            Route::get('/', [DominioController::class, 'index']);
            Route::post('/', [DominioController::class, 'store']);
            Route::get('/{id}', [DominioController::class, 'show']);
            Route::put('/{id}', [DominioController::class, 'update']);
            Route::delete('/{id}', [DominioController::class, 'destroy']);
        });

        Route::prefix('salas')->group(function () {
            Route::get('/', [SalaController::class, 'index']);
            Route::post('/', [SalaController::class, 'store']);
            Route::get('/{id}', [SalaController::class, 'show']);
            Route::put('/{id}', [SalaController::class, 'update']);
            Route::delete('/{id}', [SalaController::class, 'destroy']);
        });

        Route::prefix('tag-aluno')->group(function () {
            Route::get('/', [TagAlunoController::class, 'index']);
            Route::post('/', [TagAlunoController::class, 'store']);
            Route::get('/{id}', [TagAlunoController::class, 'show']);
            Route::put('/{id}', [TagAlunoController::class, 'update']);
            Route::delete('/{id}', [TagAlunoController::class, 'destroy']);
        });

        Route::prefix('tags')->group(function () {
            Route::get('/', [TagController::class, 'index']);
            Route::post('/', [TagController::class, 'store']);
            Route::get('/{id}', [TagController::class, 'show']);
            Route::put('/{id}', [TagController::class, 'update']);
            Route::delete('/{id}', [TagController::class, 'destroy']);
        });

        Route::prefix('tp-dominios')->group(function () {
            Route::get('/', [TpDominioController::class, 'index']);
            Route::post('/', [TpDominioController::class, 'store']);
            Route::get('/{id}', [TpDominioController::class, 'show']);
            Route::put('/{id}', [TpDominioController::class, 'update']);
            Route::delete('/{id}', [TpDominioController::class, 'destroy']);
        });

        Route::prefix('usuarios')->group(function () {
            Route::get('/', [UsersControllers::class, 'index']);
            Route::post('/', [UsersControllers::class, 'store']);
            Route::put('/{id}', [UsersControllers::class, 'update']);
            Route::delete('/{id}', [UsersControllers::class, 'destroy']);
            Route::post('/set-dark-mode/{dark_mode}', [UsersControllers::class, 'setDarkMode']);
            Route::post('/change-password', [UsersControllers::class, 'changePassword']);
        });

        Route::prefix('usuario-sala')->group(function () {
            Route::get('/', [UsersSalaController::class, 'index']);
            Route::post('/', [UsersSalaController::class, 'store']);
            Route::get('/{id}', [UsersSalaController::class, 'show']);
            Route::put('/{id}', [UsersSalaController::class, 'update']);
            Route::delete('/{id}', [UsersSalaController::class, 'destroy']);
        });
        

    });
});


use App\Http\Controllers\TenantController;
Route::middleware([
])->group(function () {
    Route::prefix('helium')->group(function () {
        Route::prefix('tenants')->group(function () {;
            Route::get('/', [TenantController::class, 'index']);
            Route::post('/store', [TenantController::class, 'store']);
        });
        Route::get('/status', function (Request $request) {
            return response()->json(['status' => 'Helium API is operational']);
        });
    });
});
