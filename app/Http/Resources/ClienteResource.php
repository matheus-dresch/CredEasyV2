<?php

namespace App\Http\Resources;

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
        $dados['qtd_emprestimos'] = count($emprestimos);

        $dados['total_emprestado'] = $emprestimos->reduce(function ($acum, $item) {
            if($item->status != "SOLICITADO") return $acum + $item->valor;
        }) ?: 0;

        $dados['total_pago'] = 0;
        foreach ($this->emprestimos as $emprestimo) {
            if (! $emprestimo->parcelas()->exists()) continue;

            $dados['total_pago'] = $emprestimo->parcelas->reduce(function($acum, $item) {
                if ($item->status === "PAGA") return $acum + $item->valor_pago;
            });
        }

        return $dados;
    }

    private function outrosDados() {
        $ultimoEmprestimo = $this->emprestimos()->first();
        $proximaParcela = $this->proximaParcela();

        return ['ultimo_emprestimo' => $ultimoEmprestimo, 'proximaParcela' => $proximaParcela];
    }
}
