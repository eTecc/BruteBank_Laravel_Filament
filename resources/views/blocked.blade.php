<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Access blocked · BruteBank</title>
    <style>
        :root{color-scheme:light;--ink:#191919;--pink:#ff3b5c;--paper:#efebe4}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;background:var(--paper);color:var(--ink);font-family:Arial,Helvetica,sans-serif}
        header{min-height:76px;padding:16px clamp(20px,5vw,64px);display:flex;align-items:center;gap:20px;border-bottom:1px solid var(--ink)}
        .brand{display:block;width:154px;height:auto}
        .label{border-left:1px solid #19191959;padding-left:20px;color:var(--pink);font:700 11px/1.5 ui-monospace,monospace;letter-spacing:.1em;text-transform:uppercase}
        main{width:min(calc(100% - 48px),620px);margin:clamp(32px,8vh,80px) auto;border:2px solid var(--ink);background:#fff;box-shadow:8px 8px 0 var(--pink);text-align:center}
        .content{padding:clamp(24px,5vw,48px)}
        .shield{width:64px;height:64px;padding:14px;margin:0 auto 24px;background:var(--ink);color:var(--pink);box-shadow:4px 4px 0 var(--pink)}
        .eyebrow{color:var(--pink);font:700 11px/1.5 ui-monospace,monospace;letter-spacing:.12em;text-transform:uppercase}
        h1{margin:12px 0 20px;font-size:clamp(32px,7vw,48px);line-height:1.05;letter-spacing:-.04em;text-transform:uppercase}
        p{font-size:14px;line-height:1.7;color:#514d48}
        footer{padding:16px;background:var(--ink);color:#fff;font:700 11px/1.6 ui-monospace,monospace;text-transform:uppercase}
    </style>
</head>
<body>
    <header>@include('brutebank::logo')<span class="label">Website protection</span></header>
    <main>
        <div class="content">
            <svg class="shield" viewBox="0 0 32 32" fill="none" aria-hidden="true"><path d="M16 3 27 7v8c0 7-4.7 11.5-11 14C9.7 26.5 5 22 5 15V7l11-4Z" stroke="currentColor" stroke-width="2.2"/><path d="m12 12 8 8m0-8-8 8" stroke="currentColor" stroke-width="2.2"/></svg>
            <div class="eyebrow">// BruteBank security</div>
            <h1>Access blocked</h1>
            <p>Your IP address has been blocked by this website’s security protection.</p>
            <p>If you believe this is a mistake, contact the website administrator.</p>
        </div>
        <footer>Protected by BruteBank · Request denied</footer>
    </main>
</body>
</html>
