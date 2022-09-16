<?php

namespace App\Services;

use App\Models\Emprestimo;
use App\Models\Parcela;
use DateInterval;
use DateTimeImmutable;
use DomainException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ParcelaService
{
    public function criaParcelas(Emprestimo $emprestimo) {
        $quantidadeParcelas = $emprestimo->qtd_parcelas;
        $valorFinal = $emprestimo->valor_final;
        $valorParcela = $valorFinal / $quantidadeParcelas;

        $dataAtual = new DateTimeImmutable();
        $dataVencimento = $dataAtual->add(new DateInterval('P1M'));

        $parcelasCriadas = [];

        for ($i = 1; $i <= $quantidadeParcelas; $i++) {
            $parcelasCriadas[] = [
                'valor' => $valorParcela,
                'numero' => $i,
                'emprestimo_id' => $emprestimo->id,
                'data_vencimento' => $dataVencimento
            ];

            $dataVencimento = $dataVencimento->add(new DateInterval('P1M'));
        }

        Parcela::insert($parcelasCriadas);
    }

    public function pagaParcela(int $id, EmprestimoService $emprestimoService)
    {
        $parcela = Parcela::find($id);
        if (! $parcela) throw new NotFoundHttpException('A parcela não existe');

        if ($parcela->emprestimo->cliente_id != Auth::user()->id) throw new AuthorizationException('Você não pode pagar a parcela de outra pessoa');

        if ($parcela->status === 'PAGA') throw new DomainException('Você não pode pagar uma parcela já paga');

        $parcela->status = 'PAGA';

        if ($parcela->data_vencimento < now()) {
            $parcela->valor_pago = $parcela->valor * 1.02;
        } else {
            $parcela->valor_pago = $parcela->valor;
        }

        $parcela->data_pagamento = now();
        $parcela->save();

        $emprestimo = $emprestimoService->getEmprestimo($parcela->emprestimo_id);
        if ($parcela->numero === $emprestimo->parcelas->count()) $emprestimoService->quitaEmprestimo($parcela->emprestimo);

        return $parcela;
    }
}
