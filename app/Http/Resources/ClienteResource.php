<?php

namespace App\Http\Resources;

use App\Models\Parcela;
use Illuminate\Http\Resources\Json\JsonResource;

class ClienteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'numero_celular' => $this->numero_celular,
            'nome' => $this->nome,
            'endereco' => $this->endereco,
            'profissao' => $this->profissao,
            'tipo' => $this->tipo,
            'renda' => $this->renda,
            'dados' => [...$this->calculaNumeros(), ...$this->outrosDados()],
            'emprestimos' => $this->emprestimos
        ];
    }

    private function calculaNumeros()
    {
        $emprestimos = $this->emprestimos;
        $dados['qtd_emprestimos'] = $emprestimos->count();

        $dados['total_emprestado'] = $emprestimos->where('status', '!=', 'SOLICITADO')
            ->reduce(fn ($acum, $item) => $acum + $item->valor);

        $dados['total_pago'] = 0;
        foreach ($this->emprestimos as $emprestimo) {
            if (!$emprestimo->parcelas()->exists()) continue;

            $parcelas = $emprestimo->parcelas->where('status', 'PAGA');
            $dados['total_pago'] += $parcelas->reduce(fn ($acum, $item) => $acum + $item->valor_pago);
        }

        return $dados;
    }

    private function outrosDados()
    {

        $ultimoEmprestimo = $this->emprestimos()->orderBy('data_solicitacao', 'DESC')->first();
        $proximaParcela = $this->proximaParcela();

        return ['ultimo_emprestimo' => $ultimoEmprestimo, 'proxima_parcela' => $proximaParcela];
    }

    private function proximaParcela()
    {
        $parcela = Parcela::select('parcela.emprestimo_id', 'parcela.data_vencimento', 'parcela.valor')
            ->join('emprestimo', 'parcela.emprestimo_id', 'emprestimo.id')
            ->where('cliente_id', $this->id)
            ->where('parcela.status', '!=', 'PAGA')
            ->orderBy('parcela.data_vencimento')
            ->limit(1)
            ->first();

        return $parcela;
    }
}
