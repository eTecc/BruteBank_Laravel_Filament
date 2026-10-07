<?php

namespace BruteBank\LaravelFilament\Http\Middleware;

use BruteBank\LaravelFilament\Services\BruteBankClient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceBlocklist
{
    public function __construct(private readonly BruteBankClient $client) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->client->isBlocked((string) $request->ip())) {
            abort(403, 'Access denied by BruteBank security.');
        }

        return $next($request);
    }
}
