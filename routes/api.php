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
    Route::middleware('permissao')->group(function () {
        Route::get('clientes', [ClienteController::class, 'listaClientes']);
        Route::get('clientes/{id}', [ClienteController::class, 'detalhaCliente']);
        Route::get('clientes/{id}/emprestimos', [ClienteController::class, 'emprestimosCliente']);

        Route::patch('emprestimos/{id}', [EmprestimoController::class, 'atualiza']);
    });

    Route::get('clientes/@eu', [ClienteController::class, 'detalhaClienteLogado']);
    Route::get('clientes/@eu/emprestimos', [ClienteController::class, 'emprestimosClienteLogado']);

    Route::get('emprestimos', [EmprestimoController::class, 'lista']);
    Route::get('emprestimos/{id}', [EmprestimoController::class, 'detalha']);
    Route::get('emprestimos/{id}/parcelas', [ClienteController::class, 'parcelas']);

    Route::get('parcelas/{id}', [ParcelaController::class, 'detalha']);

    Route::post('emprestimos', [ClienteController::class, 'registra']);

    Route::patch('parcelas/{id}', [ClienteController::class, 'pagaParcela']);
});

Route::post('clientes', [ClienteController::class, 'registra']);
Route::post('login', [LoginController::class, 'autentica']);
