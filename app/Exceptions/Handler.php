<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use DomainException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (AuthenticationException $err, $request) {
            return $this->respostaErro($err->getMessage(), 401);
        });

        $this->renderable(function (AuthorizationException $err, $request) {
            return $this->respostaErro($err->getMessage(), 403);
        });

        $this->renderable(function (NotFoundHttpException $err, $request) {
            return $this->respostaErro($err->getMessage() ?: 'Este recurso é inexistente', 404);
        });

        $this->renderable(function (DomainException $err, $request) {
            return $this->respostaErro($err->getMessage(), 422);
        });

        // $this->renderable(function (Throwable $err, $request) {
        //     return $this->respostaErro($err->getMessage(), 500, [ 'erro' => $err->getPrevious() ]);
        // });

    }
}
