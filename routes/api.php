<?php

use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\EmprestimoController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ParcelaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('clientes/@eu', [ClienteController::class, 'detalhaClienteLogado']);
    Route::get('clientes/@eu/emprestimos', [ClienteController::class, 'emprestimosClienteLogado']);

    Route::middleware('permissao')->group(function () {
        Route::get('clientes', [ClienteController::class, 'listaClientes']);
        Route::get('clientes/{id}', [ClienteController::class, 'detalhaCliente']);
        Route::get('clientes/{id}/emprestimos', [ClienteController::class, 'emprestimosCliente']);

        Route::patch('emprestimos/{id}', [EmprestimoController::class, 'atualizaEmprestimo']);
    });

    Route::get('emprestimos', [EmprestimoController::class, 'listaEmprestimos']);
    Route::get('emprestimos/{id}', [EmprestimoController::class, 'detalhaEmprestimo']);
    Route::get('emprestimos/{id}/parcelas', [EmprestimoController::class, 'parcelasEmprestimo']);
    Route::post('emprestimos', [EmprestimoController::class, 'registraEmprestimo']);
    Route::delete('emprestimos/{id}', [EmprestimoController::class, 'cancelaEmprestimo']);

    Route::get('parcelas/{id}', [ParcelaController::class, 'detalhaParcela']);
    Route::patch('parcelas/{id}', [ParcelaController::class, 'pagaParcela']);
});

Route::post('clientes', [ClienteController::class, 'registraCliente']);
Route::post('login', [LoginController::class, 'autentica']);
