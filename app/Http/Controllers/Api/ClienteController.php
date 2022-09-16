<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistroClienteRequest;
use App\Http\Resources\ClienteResource;
use App\Models\Cliente;
use App\Models\Parcela;
use App\Services\ClienteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClienteController extends Controller
{

    use ApiResponse;

    public function __construct(
        private ClienteService $service
    ) {
    }

    public function registraCliente(RegistroClienteRequest $registroClienteRequest)
    {
        $dadosRegistro = $registroClienteRequest->toArray();

        if ($this->service->registraCliente($dadosRegistro)) {
            return $this->respostaSucesso([], 'Cadastrado com sucesso, faça login para continuar');
        }
    }

    //* Funções para gestores/diretores
    public function listaClientes()
    {
        $clientes = Cliente::all();

        return $this->respostaSucesso(['clientes' => $clientes]);
    }

    public function detalhaCliente(int $id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) throw new NotFoundHttpException('Cliente não encontrado');

        return $this->respostaSucesso(['cliente' => new ClienteResource($cliente)]);
    }

    public function emprestimosCliente(int $id, Request $request)
    {
        $dadosResposta = $this->service->getEmprestimosCliente(
            $id,
            boolval($request->query('cliente'))
        );

        return $this->respostaSucesso($dadosResposta);
    }

    //* Funções autenticadas
    public function detalhaClienteLogado()
    {
        $cliente = Auth::user();

        return $this->respostaSucesso(['cliente' => new ClienteResource($cliente)]);
    }

    public function emprestimosClienteLogado()
    {
        $cliente = Auth::user();

        $emprestimos = $cliente->emprestimos;
        if (!$emprestimos) throw new NotFoundHttpException('Você não possui nenhum empréstimo');

        return $this->respostaSucesso(['emprestimos' => $emprestimos]);
    }
}
