<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClienteService
{
    public function getCliente(int $id): Cliente
    {
        $cliente = Cliente::find($id);
        if (! $cliente) throw new NotFoundHttpException("O cliente solicitado não existe");

        return $cliente;
    }

    public function getTodosClientes()
    {
        return Cliente::all();
    }

    public function getEmprestimosCliente(int $id, bool $comCliente = false)
    {
        $cliente = $this->getCliente($id);

        $emprestimos = $cliente->emprestimos;
        return $comCliente ? ['cliente' => $cliente, 'emprestimos' => $emprestimos] : ['emprestimos' => $emprestimos];
    }

    public function registraCliente($dadosRegistro)
    {
        $dadosRegistro['renda'] = intval(preg_replace('/[\D]/', '', $dadosRegistro['renda'])) / 100;
        $dadosRegistro['endereco'] = $this->enderecoParaString($dadosRegistro);
        $dadosRegistro['senha'] = Hash::make($dadosRegistro['senha']);

        return Cliente::create($dadosRegistro);
    }

    private function enderecoParaString(array $dados)
    {
        [
            'cep' => $cep,
            'uf' => $uf,
            'cidade' => $cidade,
            'bairro' => $bairro,
            'rua' => $rua,
            'numcasa' => $numcasa
        ] = $dados;

        return "$uf, $cidade - $cep, $bairro, $rua, Nº $numcasa";
    }
}
