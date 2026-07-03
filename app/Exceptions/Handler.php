<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException && $exception->getStatusCode() === 403) {
            return redirect()->route('login')
                ->with('error', 'You do not have permission to perform this action.');
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return redirect()->route('login')
                ->with('error', 'Invalid request method.');
        }

        return parent::render($request, $exception);
    }
}
