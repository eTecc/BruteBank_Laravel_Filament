<?php

namespace BruteBank\LaravelFilament\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TwoFactorController
{
    public function show(): View
    {
        return view('brutebank::two-factor');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $hash = $request->session()->get('brutebank_2fa_code');
        $expires = (int) $request->session()->get('brutebank_2fa_expires', 0);
        $challengeUserId = $request->session()->get('brutebank_2fa_user_id');

        if (! $hash || $challengeUserId !== $request->user()->getAuthIdentifier() || $expires < now()->timestamp || ! password_verify($request->string('code')->toString(), $hash)) {
            return back()->withErrors(['code' => 'The verification code is invalid or expired.']);
        }

        $request->session()->put('brutebank_2fa_verified_user_id', $request->user()->getAuthIdentifier());
        $request->session()->forget(['brutebank_2fa_code', 'brutebank_2fa_expires', 'brutebank_2fa_user_id']);

        return redirect()->intended('/');
    }
}
