<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmprestimoResource extends JsonResource
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
            "id" => $this->id,
            "nome" => $this->nome,
            "valor" => $this->valor,
            "valor_final" => $this->valor_final,
            "taxa_juros" => $this->taxa_juros * 100 - 100,
            "qtd_parcelas" => $this->qtd_parcelas,
            "status" => $this->status,
            "data_solicitacao" => $this->data_solicitacao,
            "data_quitacao" => $this->data_quitacao,
            "teste" => $this->parcelas->reduce(function ($acum, $item) {
                return $acum + $item->valor_pago;
            })
        ];
    }
}
