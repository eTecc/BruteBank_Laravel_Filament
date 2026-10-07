<?php

namespace BruteBank\LaravelFilament\Http\Middleware;

use BruteBank\LaravelFilament\Models\Settings;
use Closure;
use Illuminate\Http\Request;
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

        if (! $request->session()->has('brutebank_2fa_code')
            || (int) $request->session()->get('brutebank_2fa_expires', 0) < now()->timestamp
            || $request->session()->get('brutebank_2fa_user_id') !== $user->getAuthIdentifier()) {
            abort_unless(filled($user->email), 403, 'Two-factor verification requires an email address on your account.');
            $code = (string) random_int(100000, 999999);
            $request->session()->put([
                'brutebank_2fa_code' => password_hash($code, PASSWORD_DEFAULT),
                'brutebank_2fa_expires' => now()->addSeconds((int) config('brutebank.challenge_ttl', 600))->timestamp,
                'brutebank_2fa_user_id' => $user->getAuthIdentifier(),
            ]);
            \Illuminate\Support\Facades\Mail::raw("Your verification code is {$code}. It expires in 10 minutes.", function ($message) use ($user) {
                $message->to($user->email)->subject('Your BruteBank verification code');
            });
        }

        return redirect()->guest(route('brutebank.2fa.challenge'));
    }
}
