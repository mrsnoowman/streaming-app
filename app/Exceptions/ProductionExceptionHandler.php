<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ProductionExceptionHandler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): Response
    {
        // Handle 404 errors with custom view
        if ($e instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        // Handle other HTTP exceptions
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            
            switch ($statusCode) {
                case 403:
                    return response()->view('errors.403', [], 403);
                case 500:
                    return response()->view('errors.500', [], 500);
                default:
                    return response()->view('errors.generic', ['code' => $statusCode], $statusCode);
            }
        }

        // Log all exceptions for production monitoring
        $this->logException($request, $e);

        // For production, show generic error page
        if (app()->environment('production')) {
            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $e);
    }

    /**
     * Log exception with context
     */
    private function logException(Request $request, Throwable $e): void
    {
        $context = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'exception_class' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];

        \Log::error('Application Exception', $context);
    }
}
