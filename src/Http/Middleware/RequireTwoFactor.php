<?php

namespace BruteBank\LaravelFilament\Http\Middleware;

use BruteBank\LaravelFilament\Models\Settings;
use BruteBank\LaravelFilament\Services\BruteBankClient;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! Settings::current()->two_factor_enabled) {
            return $next($request);
        }

        if ($request->session()->get('brutebank_2fa_verified_user_id') === $user->getAuthIdentifier()) {
            return $next($request);
        }

        if ($request->routeIs('brutebank.2fa.*')) {
            return $next($request);
        }

        if (! $request->session()->has('brutebank_2fa_request_id')
            || (int) $request->session()->get('brutebank_2fa_expires', 0) < now()->timestamp
            || $request->session()->get('brutebank_2fa_user_id') !== $user->getAuthIdentifier()) {
            abort_unless(filled($user->email), 403, 'Two-factor verification requires an email address on your account.');
            try {
                $id = app(BruteBankClient::class)->createTwoFactor(
                    (string) $user->getAuthIdentifier(), $user->email, (string) $request->ip()
                );
            } catch (\Throwable) {
                abort(503, 'BruteBank verification is temporarily unavailable. Please try again.');
            }
            $request->session()->forget('brutebank_2fa_code');
            $request->session()->put([
                'brutebank_2fa_request_id' => $id,
                'brutebank_2fa_expires' => now()->addSeconds((int) config('brutebank.challenge_ttl', 240))->timestamp,
                'brutebank_2fa_user_id' => $user->getAuthIdentifier(),
            ]);
            Log::info('BruteBank 2FA: API challenge created', ['user_id' => $user->getAuthIdentifier(), 'request_id' => $id]);
        }

        return redirect()->guest(route('brutebank.2fa.challenge'));
    }
}
