<?php


namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @throws \Exception
     */
    public function report(Throwable $exception): void
    {
        try {
            \Log::error($exception->getMessage(), [
                'exception' => $exception::class,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ]);
        } catch (\Throwable $loggingError) {
            error_log('Error trying to log an exception : '.$loggingError->getMessage());
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
