<?php

namespace BruteBank\LaravelFilament\Http\Middleware;

use BruteBank\LaravelFilament\Services\BruteBankClient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SendHeartbeat
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        // Run after the response so API latency does not delay panel rendering.
        app(BruteBankClient::class)->sendHeartbeat();
    }
}
