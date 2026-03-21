<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\{
    InitializeTenancyByDomain,
    PreventAccessFromCentralDomains
};


use App\Http\Controllers\Client\{
    AuthController as AuthClientController,
    ClientController,
    TipoDominioController,
    DominioController,
    UsersControllers,
    ArquivoController,
    EscritorioController,
    PessoaController,
    ProcuracaoController,
    PessoaAnexoController, // Added PessoaAnexoController
    ProcessoController,
    ProcessoAdvogadoController,
    ProcessoLocalizadorController,
    ProcessoMovimentoController,
    ProcessoAudienciaController,
    ProcessoAtaController,
    ProcessoAnexoController,
    ConfigController,
    ChamadoController,
    ChamadoMovimentoController,
    PdfSignController,
    DashboardController,
    CertificadoController
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

    Route::post('/pdf/sign', [PdfSignController::class, 'sign']);
    Route::get('/pdf/signature-info/{filename}', [PdfSignController::class, 'getPdfSimpleSignature']);
    Route::get('/pdf/validate-signature/{filename}', [PdfSignController::class, 'isValidSignature']);
    Route::post('/pdf/certificate-expiry', [PdfSignController::class, 'getCertificateExpiry']);
    Route::prefix('arquivo')->group(function () {
        Route::match(['get', 'post'], '/{id}/{download?}', [ArquivoController::class, 'get']);
    });

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
        Route::prefix('tipo-dominio')->group(function (){
            Route::match(['get', 'post'], '/', [TipoDominioController::class, 'index']);
            Route::post('/store', [TipoDominioController::class, 'store']);
            Route::put('/{tipo_dominio_id}', [TipoDominioController::class, 'update']);
            Route::get('/get/{dominio_id}', [TipoDominioController::class, 'get']);
            Route::delete('/{dominio_id}', [TipoDominioController::class, 'destroy']);
        });
        Route::prefix('dominio')->group(function (){
            Route::match(['get', 'post'], '/index/{tipo_dominio_id}', [DominioController::class, 'index']);
            Route::post('/store', [DominioController::class, 'store']);
            Route::put('/{dominio_id}', [DominioController::class, 'update']);
            Route::get('/get/{dominio_id}', [DominioController::class, 'get']);
            Route::delete('/{dominio_id}', [DominioController::class, 'destroy']);
        });
        Route::prefix('usuarios')->group(function (){
            Route::get('/', [UsersControllers::class, 'index']);
            Route::post('/', [UsersControllers::class, 'store']);
            Route::get('/set-escritorio/{escritorio_id}', [UsersControllers::class, 'setEscritorio']);
            Route::get('/set-darkmode/{dark_mode}', [UsersControllers::class, 'setDarkMode']);
            Route::put('/{user_id}', [UsersControllers::class, 'update']);
            Route::delete('/{user_id}', [UsersControllers::class, 'destroy']);
            Route::post('/change-password', [UsersControllers::class, 'changePassword']);
        });

        Route::prefix('escritorio')->group(function (){
            Route::get('/', [EscritorioController::class, 'index']);
            Route::post('/', [EscritorioController::class, 'store']);
            Route::put('/{escritorio_id}', [EscritorioController::class, 'update']);
            Route::get('/get/{escritorio_id}', [EscritorioController::class, 'getById']);
            Route::delete('/{escritorio_id}', [EscritorioController::class, 'destroy']);
        });
        Route::prefix('pessoa')->group(function (){
            Route::get('/', [PessoaController::class, 'index']);
            Route::post('/{pessoa_id}/ficha-cadastral', [PessoaController::class, 'ficha_cadastral']);
            Route::post('/', [PessoaController::class, 'store']);
            Route::put('/{pessoa_id}', [PessoaController::class, 'update']);
            Route::get('/get/{pessoa_id}', [PessoaController::class, 'getById']);
            Route::delete('/{pessoa_id}', [PessoaController::class, 'destroy']);

        });
        Route::prefix('procuracao')->group(function (){
            Route::get('/', [ProcuracaoController::class, 'index']);
            Route::post('/', [ProcuracaoController::class, 'store']);
            Route::post('/{procuracao_id}/gerar', [ProcuracaoController::class, 'gerarProcuracao']);
            Route::put('/{procuracao_id}', [ProcuracaoController::class, 'update']);
            Route::get('/get/{procuracao_id}', [ProcuracaoController::class, 'getById']);
            Route::delete('/{procuracao_id}', [ProcuracaoController::class, 'destroy']);
        });
        Route::prefix('pessoa-anexo')->group(function (){
            Route::get('/', [PessoaAnexoController::class, 'index']);
            Route::post('/', [PessoaAnexoController::class, 'store']);
            Route::put('/{pessoa_anexo_id}', [PessoaAnexoController::class, 'update']);
            Route::get('/get/{pessoa_anexo_id}', [PessoaAnexoController::class, 'getById']);
            Route::delete('/{pessoa_anexo_id}', [PessoaAnexoController::class, 'destroy']);
            Route::get('/download/{pessoa_anexo_id}', [PessoaAnexoController::class, 'download']);
        });
        Route::prefix('processo')->group(function (){
            Route::get('/', [ProcessoController::class, 'index']);
            Route::post('/', [ProcessoController::class, 'store']);
            Route::put('/{processo_id}', [ProcessoController::class, 'update']);
            Route::get('/get/{processo_id}', [ProcessoController::class, 'getById']);
            Route::delete('/{processo_id}', [ProcessoController::class, 'destroy']);
        });
        Route::prefix('processo-advogado')->group(function (){
            Route::get('/', [ProcessoAdvogadoController::class, 'index']);
            Route::post('/', [ProcessoAdvogadoController::class, 'store']);
            Route::put('/{processo_advogado_id}', [ProcessoAdvogadoController::class, 'update']);
            Route::get('/get/{processo_advogado_id}', [ProcessoAdvogadoController::class, 'getById']);
            Route::delete('/{processo_advogado_id}', [ProcessoAdvogadoController::class, 'destroy']);
        });
        Route::prefix('processo-localizador')->group(function (){
            Route::get('/', [ProcessoLocalizadorController::class, 'index']);
            Route::post('/', [ProcessoLocalizadorController::class, 'store']);
            Route::put('/{processo_localizador_id}', [ProcessoLocalizadorController::class, 'update']);
            Route::get('/get/{processo_localizador_id}', [ProcessoLocalizadorController::class, 'getById']);
            Route::delete('/{processo_localizador_id}', [ProcessoLocalizadorController::class, 'destroy']);
        });
        Route::prefix('processo-movimento')->group(function (){
            Route::get('/', [ProcessoMovimentoController::class, 'index']);
            Route::post('/', [ProcessoMovimentoController::class, 'store']);
            Route::put('/{processo_movimento_id}', [ProcessoMovimentoController::class, 'update']);
            Route::get('/get/{processo_movimento_id}', [ProcessoMovimentoController::class, 'getById']);
            Route::delete('/{processo_movimento_id}', [ProcessoMovimentoController::class, 'destroy']);
        });
        Route::prefix('processo-audiencia')->group(function (){
            Route::get('/', [ProcessoAudienciaController::class, 'index']);
            Route::post('/', [ProcessoAudienciaController::class, 'store']);
            Route::put('/{processo_audiencia_id}', [ProcessoAudienciaController::class, 'update']);
            Route::get('/get/{processo_audiencia_id}', [ProcessoAudienciaController::class, 'getById']);
            Route::delete('/{processo_audiencia_id}', [ProcessoAudienciaController::class, 'destroy']);
        });
        Route::prefix('processo-ata')->group(function (){
            Route::get('/', [ProcessoAtaController::class, 'index']);
            Route::post('/', [ProcessoAtaController::class, 'store']);
            Route::put('/{processo_ata_id}', [ProcessoAtaController::class, 'update']);
            Route::get('/get/{processo_ata_id}', [ProcessoAtaController::class, 'getById']);
            Route::delete('/{processo_ata_id}', [ProcessoAtaController::class, 'destroy']);
        });

        Route::prefix('processo-anexo')->group(function (){
            Route::get('/', [ProcessoAnexoController::class, 'index']);
            Route::post('/', [ProcessoAnexoController::class, 'store']);
            Route::put('/{processo_anexo_id}', [ProcessoAnexoController::class, 'update']);
            Route::get('/get/{processo_anexo_id}', [ProcessoAnexoController::class, 'getById']);
            Route::delete('/{processo_anexo_id}', [ProcessoAnexoController::class, 'destroy']);
            Route::get('/download/{processo_anexo_id}', [ProcessoAnexoController::class, 'download']);
        });

        Route::prefix('chamado')->group(function (){
            Route::get('/', [ChamadoController::class, 'index']);
            Route::post('/', [ChamadoController::class, 'store']);
            Route::put('/{chamado_id}', [ChamadoController::class, 'update']);
            Route::get('/get/{chamado_id}', [ChamadoController::class, 'getById']);
            Route::delete('/{chamado_id}', [ChamadoController::class, 'destroy']);
        });
        Route::prefix('chamado-movimento')->group(function (){
            Route::get('/', [ChamadoMovimentoController::class, 'index']);
            Route::post('/', [ChamadoMovimentoController::class, 'store']);
        });

        Route::prefix('certificado')->group(function (){
            Route::get('/', [CertificadoController::class, 'index']);
            Route::post('/', [CertificadoController::class, 'store']);
            Route::get('/get/{certificado_id}', [CertificadoController::class, 'getById']);
            Route::delete('/{certificado_id}', [CertificadoController::class, 'destroy']);
        });
        Route::prefix('config')->group(function (){
            Route::get('/getconfig', [ConfigController::class, 'getConfig']);
            Route::get('/', [ConfigController::class, 'index']);
            Route::post('/', [ConfigController::class, 'store']);
            Route::put('/{config_id}', [ConfigController::class, 'update']);
            Route::get('/get/{config_id}', [ConfigController::class, 'getById']);
            Route::delete('/{config_id}', [ConfigController::class, 'destroy']);
        });

        Route::prefix('assinar')->group(function(){
            Route::post('/pdf', [PdfSignController::class, 'assinar']);
        });

        Route::prefix('dashboard')->group(function () {
            Route::get('/', [DashboardController::class, 'index']);
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
