<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            if ($exception->getCode() == 404) {
                return response()->view('errors.404', [], 404);
            }
            if ($exception->getCode() == 500) {
                return response()->view('errors.500', [], 500);
            }
            if ($exception->getCode() == 403) {
                return response()->view('errors.403', [], 403);
            }
            if ($exception->getCode() == 419) {
                return response()->view('errors.419', [], 419);
            }
        }

        return parent::render($request, $exception);
    }

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
