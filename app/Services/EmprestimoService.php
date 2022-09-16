<?php

namespace App\Services;

use App\Models\Emprestimo;
use DomainException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
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

        return ['parcelas' => $parcelas, 'proxima_parcela' => $emprestimo->proximaParcela() ];
    }

    public function getTodosEmprestimos()
    {
        return Emprestimo::all();
    }

    public function registraEmprestimo(array $emprestimoData)
    {
        $emprestimoData['valor'] = intval(preg_replace('/[\D]/', '', $emprestimoData['valor'])) / 100;
        if ($emprestimoData['valor'] < 1000) throw new DomainException("O valor do empréstimo deve ser de no mínimo R$ 1.000,00");

        $emprestimoData['valor_final'] = $emprestimoData['valor'] * 1.1;
        $emprestimoData['data_solicitacao'] = now();

        $emprestimoData['cliente_id'] = Auth::user()->id;

        if ($emprestimoData['valor_final'] / $emprestimoData['qtd_parcelas'] < 200) throw new DomainException("O valor mínimo de cada parcela é R$ 200,00");

        try {
            $emprestimo = Emprestimo::create($emprestimoData);
        } catch(QueryException $err) {
            throw new \Exception("Houve um problema ao solicitar seu empréstimo, tente novamente mais tarde", 0, $err);
        }

        return $emprestimo;
    }

    public function aprovaEmprestimo(float $taxa, int $id, ParcelaService $parcelaService): Emprestimo
    {
        $emprestimo = $this->getEmprestimo($id);
        if ($emprestimo->status != "SOLICITADO") throw new AuthorizationException("Este empréstimo não pode mais ser atualizado");

        if($emprestimo->cliente_id === Auth::user()->id) throw new AuthorizationException("Você não pode aprovar seu próprio empréstimo");

        $emprestimo->status = "APROVADO";
        $emprestimo->taxa_juros = ($taxa / 100) + 1;
        $emprestimo->valor_final = $emprestimo->valor * $emprestimo->taxa_juros;

        $emprestimo->save();

        $parcelaService->criaParcelas($emprestimo);

        return $emprestimo;
    }

    public function rejeitaEmprestimo(int $id): Emprestimo
    {
        $emprestimo = $this->getEmprestimo($id);
        $emprestimo->status = 'REJEITADO';

        $emprestimo->save();

        return $emprestimo;
    }

    public function cancelaEmprestimo($id)
    {
        $emprestimo = $this->getEmprestimo($id);

        if ($emprestimo->cliente_id != Auth::user()->id) throw new AuthorizationException("Você não pode cancelar o empréstimo de outra pessoa");
        if ($emprestimo->status != 'SOLICITADO') throw new AuthorizationException("Você não pode cancelar um empréstimo que já foi " . strtolower($emprestimo->status));

        $emprestimo->status = "CANCELADO";
        $emprestimo->save();

        return $emprestimo;
    }

    public function quitaEmprestimo(Emprestimo $emprestimo)
    {
        $emprestimo->status = "QUITADO";
        $emprestimo->data_quitacao = now();

        $emprestimo->valor_final = $emprestimo->parcelas->reduce(function ($acum, $item) {
            return $acum + $item->valor_pago;
        });

        $emprestimo->save();
    }
}
