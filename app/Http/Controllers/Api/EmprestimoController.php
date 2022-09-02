<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmprestimoService;
use App\Traits\ApiResponse;

class EmprestimoController extends Controller
{
    use ApiResponse;

    public function __construct(
        private EmprestimoService $service
    )
    {

    }

    public function listaEmprestimos()
    {
        $emprestimos = $this->service->getTodosEmprestimos();

        return $this->respostaSucesso(['emprestimos' => $emprestimos]);
    }

    public function detalhaEmprestimo(int $id)
    {
        $emprestimo = $this->service->getEmprestimo($id);

        return $this->respostaSucesso(['emprestimo' => $emprestimo]);
    }

    public function parcelasEmprestimo(int $id)
    {
        $parcelas = $this->service->getParcelasEmprestimo($id);

        return $this->respostaSucesso(['parcelas' => $parcelas]);
    }

    public function registraEmprestimos()
    {
        //
    }

    public function atualizaEmprestimo()
    {
        //
    }
}
