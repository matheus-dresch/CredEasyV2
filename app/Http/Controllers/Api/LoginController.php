<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ApiResponse;

    public function autentica(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'senha' => ['required']
        ]);

        $credenciaisCorretas = Auth::attempt([
            'email' => $request->email,
            'password' => $request->senha
        ]);

        if (! $credenciaisCorretas) throw new AuthenticationException('Usuário ou senha inválidos');

        /** @var Cliente $cliente */
        $cliente = Auth::user();
        $token = $cliente->createToken('token')->plainTextToken;

        return $this->respostaSucesso([
            'token' => $token,
            'id' => $cliente->id,
            'gestor' => $cliente->tipo === 'GESTOR'
        ], 'Login efetuado com sucesso');
    }
}
