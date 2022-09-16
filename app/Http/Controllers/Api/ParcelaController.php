<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\EmprestimoService;
use App\Services\ParcelaService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ParcelaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private ParcelaService $service
    )
    {

    }

    public function pagaParcela(int $id)
    {
        $parcela = $this->service->pagaParcela($id, new EmprestimoService());

        return $this->respostaSucesso(['parcela' => $parcela], "Parcela número {$parcela->numero} paga com sucesso");
    }
}
