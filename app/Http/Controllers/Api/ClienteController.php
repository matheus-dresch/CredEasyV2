<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClienteController extends Controller
{

    use ApiResponse;
    public function lista()
    {
        $clientes = Cliente::all();

        return $this->respostaSucesso(['clientes' => $clientes]);
    }



    public function detalhaCliente(int $id)
    {
        $cliente = Cliente::find($id);
        if (! $cliente) throw new NotFoundHttpException('Cliente não encontrado');

        return $this->respostaSucesso(['cliente' => $cliente]);
    }

    public function emprestimosCliente(int $id, Request $request)
    {
        $dadosResposta = [];

        $cliente = Cliente::find($id);
        if (!$cliente) throw new NotFoundHttpException('Cliente não encontrado');

        $emprestimos = $cliente->emprestimos;
        if (!$emprestimos) throw new NotFoundHttpException('O cliente não possui empréstimos');

        if ($request->query('cliente')) $dadosResposta['cliente'] = $cliente;

        $dadosResposta['emprestimos'] = $emprestimos;

        return $this->respostaSucesso($dadosResposta);
    }
}
