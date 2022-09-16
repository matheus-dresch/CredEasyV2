<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AtualizaEmprestimoRequest;
use App\Http\Requests\RegistroEmprestimoRequest;
use App\Http\Resources\EmprestimoResource;
use App\Models\Emprestimo;
use App\Services\EmprestimoService;
use App\Services\ParcelaService;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmprestimoController extends Controller
{
    use ApiResponse;

    public function __construct(
        private EmprestimoService $service
    ) {
    }

    public function listaEmprestimos(Request $request)
    {
        $gestor = $request->query('gestor');

        if ($gestor && Auth::user()->tipo != "GESTOR") throw new AuthorizationException('Você não tem permissão para acessar este recurso');

        $emprestimos = $gestor ?
            Emprestimo::where('status', '=', 'SOLICITADO')->where('cliente_id', '!=', Auth::user()->id)->get() :
            Emprestimo::all();

        return $this->respostaSucesso(['emprestimos' => $emprestimos]);
    }

    public function detalhaEmprestimo(int $id)
    {
        $emprestimo = $this->service->getEmprestimo($id);

        return $this->respostaSucesso([
            'emprestimo' => new EmprestimoResource($emprestimo),
            'dados' => [
                'tem_parcelas' => $emprestimo->parcelas()->exists()
            ]
        ]);
    }

    public function parcelasEmprestimo(int $id)
    {
        $parcelas = $this->service->getParcelasEmprestimo($id);

        return $this->respostaSucesso([
            'parcelas' => $parcelas['parcelas'],
            'dados' => [
                'proxima_parcela' => $parcelas['proxima_parcela']
            ]
        ]);
    }

    public function registraEmprestimo(RegistroEmprestimoRequest $request)
    {
        $emprestimoData = $request->toArray();

        $emprestimoCriado = $this->service->registraEmprestimo($emprestimoData);

        return $this->respostaSucesso(['emprestimo' => $emprestimoCriado], 'Empréstimo solicitado com sucesso', 201);
    }

    public function atualizaEmprestimo(AtualizaEmprestimoRequest $request, int $id)
    {
        $emprestimo =
            $request->status ?
            $this->service->aprovaEmprestimo($request->taxa_juros, $id, new ParcelaService())
            :
            $this->service->rejeitaEmprestimo($id);


        return $this->respostaSucesso(['emprestimo' => $emprestimo], "O empréstimo de id $id foi atualizado com sucesso");
    }

    public function cancelaEmprestimo(int $id)
    {
        $emprestimo = $this->service->cancelaEmprestimo($id);

        return $this->respostaSucesso(['emprestimo' => $emprestimo], "O empréstimo de id $id foi cancelado com sucesso");
    }
}
