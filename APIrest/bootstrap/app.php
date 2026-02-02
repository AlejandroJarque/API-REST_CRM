<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append: [
            \App\Http\Middleware\AttachRequestContext::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function(\Throwable $e, Request $request) {
            $isApi = $request->is('api/*') || $request->expectsJson();

            if(! $isApi) {
                return null;
            }

            $make = function(int $status, string $message, $details = null) {
                return response()->json([
                    'message' => $message,
                    'details' => $details,
                ], $status);
            };

            if($e instanceof ValidationException) {
                return $make(422, 'Validation failed', [
                    'fields' => $e->errors(),
                ]);
            }

            if($e instanceof AuthenticationException) {
                return $make(401, 'Unauthenticated', null);
            }

            if($e instanceof AuthorizationException) {
                return $make(403, 'Forbidden', null);
            }

            if($e instanceof ModelNotFoundException||$e instanceof NotFoundHttpException) {
                return $make(404, 'Not Found', null);
            }

            if($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = match ($status) {
                    400 => 'Bad Request',
                    401 => 'Unauthenticated',
                    403 => 'Forbidden',
                    404 => 'Not Found',
                    405 => 'Method Not Allowed',
                    default => 'HTTP Error',
                };

                return $make($status, $message, null);
            }

            $status = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : 500;

            if($status >= 500) {
                Log::error('Unhandled exception', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'status' => $status,
                    'request_id' => $request->attributes->get('request_id'),
                ]);
            }

            return $make(500, 'Internal Server Error', null);
        });
    })->create();
