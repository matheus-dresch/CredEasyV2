<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emprestimo;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
{
    use ApiResponse;

    public function lista()
    {
        $emprestimos = Emprestimo::all();

        return $this->respostaSucesso(['emprestimos' => $emprestimos]);
    }

    public function detalha(int $id)
    {
        return Emprestimo::find($id);
    }
}
