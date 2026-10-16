<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>Verification temporarily unavailable · BruteBank</title>
    <style>
        :root{color-scheme:light;--ink:#191919;--pink:#ff3b5c;--paper:#efebe4}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;background:var(--paper);color:var(--ink);font-family:Arial,Helvetica,sans-serif}
        header{min-height:76px;padding:16px clamp(20px,5vw,64px);display:flex;align-items:center;gap:20px;border-bottom:1px solid var(--ink)}
        .brand{display:block;width:154px;height:auto}
        .label{border-left:1px solid #19191959;padding-left:20px;color:var(--pink);font:700 11px/1.5 ui-monospace,monospace;letter-spacing:.1em;text-transform:uppercase}
        main{width:min(calc(100% - 48px),620px);margin:clamp(32px,8vh,80px) auto;border:2px solid var(--ink);background:#fff;box-shadow:8px 8px 0 var(--pink);text-align:center}
        .content{padding:clamp(24px,5vw,48px)}
        .icon{width:64px;height:64px;padding:14px;margin:0 auto 24px;background:var(--ink);color:var(--pink);box-shadow:4px 4px 0 var(--pink)}
        .eyebrow{color:var(--pink);font:700 11px/1.5 ui-monospace,monospace;letter-spacing:.12em;text-transform:uppercase}
        h1{margin:12px 0 20px;font-size:clamp(28px,6vw,42px);line-height:1.1;letter-spacing:-.04em}
        p{font-size:14px;line-height:1.7;color:#514d48}
        .retry{display:block;margin-top:28px;padding:17px 24px;border:2px solid var(--ink);background:var(--pink);color:var(--ink);box-shadow:4px 4px 0 var(--ink);font:700 12px/1.5 ui-monospace,monospace;letter-spacing:.08em;text-decoration:none;text-transform:uppercase}
        .retry:hover{background:#ff5974}.retry:focus-visible{outline:3px solid var(--pink);outline-offset:5px}
        footer{padding:16px;background:var(--ink);color:#fff;font:700 10px/1.6 ui-monospace,monospace}
        @media(max-width:420px){header{gap:12px}.brand{width:126px}.label{padding-left:12px;font-size:9px}}
    </style>
</head>
<body>
    <header>@include('brutebank::logo')<span class="label">Sign-in protection</span></header>
    <main aria-labelledby="warning-title">
        <div class="content">
            <svg class="icon" viewBox="0 0 32 32" fill="none" aria-hidden="true"><path d="M16 4 29 27H3L16 4Z" stroke="currentColor" stroke-width="2"/><path d="M16 12v7m0 3v2" stroke="currentColor" stroke-width="2"/></svg>
            <div class="eyebrow">A brief pause</div>
            <h1 id="warning-title">Verification temporarily unavailable</h1>
            <p>We couldn’t complete two-factor verification right now. Please wait a moment, then try again.</p>
            <p>Your account is still protected. You’ll need to complete verification before continuing.</p>
            <a class="retry" href="{{ $retryUrl }}">Try again</a>
        </div>
        <footer>Protected by BruteBank · Secure sign-in</footer>
    </main>
</body>
</html>
