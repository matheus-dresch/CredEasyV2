<?php

namespace App\Services;

use App\Models\Emprestimo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmprestimoService
{
    public function getEmprestimo($id): Emprestimo
    {
        $emprestimo = Emprestimo::find($id);
        if (! $emprestimo) throw new NotFoundHttpException('O empréstimo solicitado não existe');

        return $emprestimo;
    }

    public function getParcelasEmprestimo($id)
    {
        $emprestimo = $this->getEmprestimo($id);

        $parcelas = $emprestimo->parcelas;
        if (count($parcelas) < 1) throw new NotFoundHttpException("O empréstimo de ID $id não possui parcelas");

        return $parcelas;
    }

    public function getTodosEmprestimos()
    {
        return Emprestimo::all();
    }
}
