<?php

namespace App\Traits;

trait ApiResponse
{
    public function respostaSucesso(array $data, string $message = '', int $status = 200)
    {
        return response()
            ->json([
                'status' => 'Sucesso',
                'message' => $message,
                'data' => $data
            ], $status);
    }

    public function respostaErro(string $message, int $status)
    {
        return response()
            ->json([
                'status' => 'Erro',
                'message' => $message,
                'data' => null
            ], $status);
    }
}
