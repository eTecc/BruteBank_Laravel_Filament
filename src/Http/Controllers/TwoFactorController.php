<?php

namespace BruteBank\LaravelFilament\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use BruteBank\LaravelFilament\Services\BruteBankClient;

class TwoFactorController
{
    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $id = $request->session()->get('brutebank_2fa_request_id');
        if (! $id || $request->session()->get('brutebank_2fa_user_id') !== $request->user()->getAuthIdentifier()
            || (int) $request->session()->get('brutebank_2fa_expires', 0) < now()->timestamp) {
            return back()->withErrors(['code' => 'Your verification request has expired. Please sign in again.']);
        }
        try {
            $approved = app(BruteBankClient::class)->verifyTwoFactor((int) $id, (string) $request->input('code'));
        } catch (\Illuminate\Http\Client\RequestException $exception) {
            $message = match ($exception->response->status()) {
                422 => 'The verification code is invalid or expired.',
                429 => 'Too many verification attempts. Please try again later.',
                default => 'BruteBank verification is unavailable. Please try again shortly.',
            };

            return back()->withErrors(['code' => $message]);
        } catch (\Throwable) {
            return back()->withErrors(['code' => 'BruteBank verification is unavailable. Please try again shortly.']);
        }
        if (! $approved) {
            return back()->withErrors(['code' => 'The verification code is invalid or expired.']);
        }
        $request->session()->put('brutebank_2fa_verified_user_id', $request->user()->getAuthIdentifier());
        $request->session()->forget(['brutebank_2fa_request_id', 'brutebank_2fa_code', 'brutebank_2fa_expires', 'brutebank_2fa_user_id']);

        return redirect()->intended('/');
    }

    public function show(): View
    {
        return view('brutebank::two-factor', [
            'verificationUrl' => rtrim(config('brutebank.api_url'), '/').'/verify_two_factor/'.rawurlencode(\BruteBank\LaravelFilament\Models\Settings::current()->public_key),
        ]);
    }

    public function verify(Request $request): JsonResponse
    {
        $id = $request->session()->get('brutebank_2fa_request_id');
        $expires = (int) $request->session()->get('brutebank_2fa_expires', 0);
        $challengeUserId = $request->session()->get('brutebank_2fa_user_id');

        if (! $id || $challengeUserId !== $request->user()->getAuthIdentifier() || $expires < now()->timestamp) {
            return response()->json(['status' => 'expired'], 410);
        }

        try {
            $status = app(BruteBankClient::class)->twoFactorStatus((int) $id);
        } catch (\Throwable) {
            return response()->json(['status' => 'unavailable'], 503);
        }
        if ($status !== 'allowed') {
            if (in_array($status, ['denied', 'expired'], true)) {
                $request->session()->forget(['brutebank_2fa_request_id', 'brutebank_2fa_expires', 'brutebank_2fa_user_id']);
            }

            return response()->json(['status' => $status]);
        }

        $request->session()->put('brutebank_2fa_verified_user_id', $request->user()->getAuthIdentifier());
        $request->session()->forget(['brutebank_2fa_request_id', 'brutebank_2fa_code', 'brutebank_2fa_expires', 'brutebank_2fa_user_id']);

        return response()->json(['status' => 'allowed', 'redirect' => redirect()->intended('/')->getTargetUrl()]);
    }
}
