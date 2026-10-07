<?php

namespace BruteBank\LaravelFilament\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class ResetTwoFactorSession
{
    public function __construct(private readonly Request $request) {}

    public function handle(Login $event): void
    {
        if (! $this->request->hasSession()) {
            return;
        }

        $this->request->session()->forget([
            'brutebank_failed_login',
            'brutebank_2fa_verified_user_id',
            'brutebank_2fa_code',
            'brutebank_2fa_request_id',
            'brutebank_2fa_expires',
            'brutebank_2fa_user_id',
        ]);
    }
}
