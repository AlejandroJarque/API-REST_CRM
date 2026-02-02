<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class AttachRequestContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->headers->get('X-Request-Id')
            ?: (string) Str::uuid();

        $request->attributes->set('request_id', $requestId);

        $context = [
            'request_id' => $requestId,
            'http_method' => $request->method(),
            'http_path' => $request->path(),
        ];

        if($request->user()) {
            $context['user_id'] = $request->user()->id;
        }

        Log::withContext($context);

        return $next($request);
    }
}