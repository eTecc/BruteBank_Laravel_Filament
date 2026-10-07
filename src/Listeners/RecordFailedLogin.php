<?php

namespace BruteBank\LaravelFilament\Listeners;

use BruteBank\LaravelFilament\Services\BruteBankClient;
use Illuminate\Auth\Events\Failed;
use Illuminate\Http\Request;

class RecordFailedLogin
{
    public function __construct(private readonly BruteBankClient $client, private readonly Request $request) {}

    public function handle(Failed $event): void
    {
        $username = (string) ($event->credentials['email'] ?? $event->credentials['username'] ?? '');
        $this->client->reportFailure((string) $this->request->ip(), $username);
    }
}
