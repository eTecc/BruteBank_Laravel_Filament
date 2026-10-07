<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Verify your sign-in</title>
<style>body{font:16px system-ui;background:#f3f4f6;display:grid;min-height:100vh;place-items:center;margin:0}.card{background:white;padding:2rem;border-radius:12px;width:min(24rem,calc(100% - 4rem));box-shadow:0 8px 32px #0001}input,button{box-sizing:border-box;width:100%;padding:.8rem;margin-top:1rem}button{background:#111827;color:white;border:0;border-radius:6px;cursor:pointer}.error{color:#b91c1c}</style></head>
<body><main class="card"><h1>Verify your sign-in</h1><p>Enter the six-digit code sent to your account email.</p>
<form method="post" action="{{ route('brutebank.2fa.verify') }}">@csrf<label for="code">Verification code</label><input id="code" name="code" inputmode="numeric" autocomplete="one-time-code" required maxlength="6">@error('code')<p class="error">{{ $message }}</p>@enderror<button type="submit">Verify</button></form></main></body></html>
