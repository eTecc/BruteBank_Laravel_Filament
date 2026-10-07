<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>Verify your sign-in · BruteBank</title>
    <style>
        :root{color-scheme:light;--ink:#191919;--pink:#ff3b5c;--paper:#efebe4}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;background:var(--paper);color:var(--ink);font-family:Arial,Helvetica,sans-serif;padding:0 20px 48px}
        .mono{font-family:ui-monospace,SFMono-Regular,Consolas,monospace}
        .security-bar{min-height:76px;margin:0 -20px;padding:0 clamp(20px,5vw,64px);display:flex;align-items:center;gap:20px;border-bottom:1px solid var(--ink)}
        .brand{display:block;width:154px;height:auto;max-height:50px;object-fit:contain}
        .bar-label{border-left:1px solid #19191959;padding-left:20px;color:var(--pink);font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase}
        .status{margin-left:auto;display:flex;align-items:center;gap:9px;font-size:10px;font-weight:700;letter-spacing:.07em;text-transform:uppercase}
        .status-dot{width:9px;height:9px;background:var(--pink)}
        .verification{width:min(100%,620px);margin:clamp(30px,7vh,68px) auto 0;border:2px solid var(--ink);box-shadow:8px 8px 0 var(--pink)}
        .verification-head{padding:36px 32px 30px;background:#fff;border-bottom:2px solid var(--ink);text-align:center}
        .icon{width:58px;height:58px;display:grid;place-items:center;margin:0 auto 22px;background:var(--ink);color:#fff;box-shadow:4px 4px 0 var(--pink)}
        .icon svg{width:31px;height:31px}
        .eyebrow{margin:0 0 11px;color:var(--pink);font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase}
        h1{margin:0;font-size:clamp(32px,6vw,48px);line-height:1.02;letter-spacing:-.045em;font-weight:900;text-transform:uppercase}
        .description{max-width:46ch;margin:15px auto 0;color:#44403c;font-size:13px;line-height:1.65}
        .content{padding:32px 36px}
        .notice{display:flex;align-items:center;gap:14px;padding:16px;border:2px solid var(--ink);background:#fff;box-shadow:4px 4px 0 var(--pink);margin-bottom:28px}
        .notice svg{width:28px;height:28px;flex-shrink:0;color:var(--pink)}
        .notice strong{display:block;font-size:12px;text-transform:uppercase;line-height:1.5}.notice small{display:block;margin-top:4px;color:#55514d;font-size:13px;line-height:1.5}
        label{display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px}
        input{width:100%;min-height:62px;padding:14px;border:2px solid var(--ink);border-radius:0;background:#fff;color:var(--ink);font-size:26px;letter-spacing:.4em;text-align:center}
        input:focus-visible,button:focus-visible{outline:3px solid var(--pink);outline-offset:4px}
        input[aria-invalid="true"]{border-color:#b91c1c}
        .hint{margin:10px 0 0;color:#55514d;font-size:12px;line-height:1.5}
        button{display:flex;justify-content:center;align-items:center;gap:14px;width:100%;min-height:54px;margin-top:22px;border:2px solid var(--ink);border-radius:0;background:var(--pink);color:var(--ink);box-shadow:4px 4px 0 var(--ink);font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer}
        button:hover{background:#ff5974}button:active{transform:translate(2px,2px);box-shadow:2px 2px 0 var(--ink)}
        .error{color:#b91c1c;font-size:13px;line-height:1.5;margin:12px 0 0}
        .checking{display:flex;align-items:center;justify-content:center;gap:12px;margin-top:22px;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase}
        .spinner{width:28px;height:28px;border:3px solid #19191926;border-top-color:var(--pink);border-radius:50%;animation:spin .9s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
        @media(prefers-reduced-motion:reduce){.spinner{animation:none}}
        .footnote{margin:0;padding:14px 18px;background:var(--ink);color:#fff;font-size:10px;line-height:1.6;letter-spacing:.03em}.footnote span{color:var(--pink);font-weight:700}
        @media(max-width:520px){body{padding:0 14px 28px}.security-bar{margin:0 -14px;min-height:62px;padding:0 16px;gap:12px}.brand{width:126px;max-height:42px}.bar-label{padding-left:12px;font-size:9px}.status{font-size:0;gap:0}.verification{margin-top:26px}.verification-head{padding:25px 20px}.content{padding:24px 20px}.description{font-size:12px}}
    </style>
</head>
<body>
    <header class="security-bar">
        @include('brutebank::logo')
        <span class="bar-label mono">Email verification</span>
        <span class="status mono"><span class="status-dot" aria-hidden="true"></span>Verification pending</span>
    </header>
    <main class="verification">
        <div class="verification-head">
            <div class="icon" aria-hidden="true">
                <svg viewBox="0 0 32 32" fill="none"><path d="M16 3 27 7v8c0 7-4.7 11.5-11 14C9.7 26.5 5 22 5 15V7l11-4Z" stroke="currentColor" stroke-width="2.2"/><path d="m11 16 3.2 3.2L21.5 12" stroke="currentColor" stroke-width="2.2"/></svg>
            </div>
            <p class="eyebrow mono">// Laravel security</p>
            <h1>Check your email</h1>
            <p class="description mono">Open your BruteBank security email and follow its verification link. Keep this page open to complete your sign-in automatically.</p>
        </div>
        <div class="content">
            <div class="notice">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 5h18v14H3V5Zm0 0 9 8 9-8" stroke="currentColor" stroke-width="1.8"/></svg>
                <div><strong class="mono">Waiting for email verification</strong><small id="verification-status" role="status">Complete verification on BruteBank. This page will check for approval automatically.</small></div>
            </div>
            <form id="verification-form" method="post" action="{{ route('brutebank.2fa.verify') }}">
                @csrf
                <div class="checking mono"><span class="spinner" aria-hidden="true"></span><span id="checking-label">Checking automatically</span></div>
                <noscript><p class="hint">Enable JavaScript to check verification and continue automatically.</p></noscript>
            </form>
        </div>
        <p class="footnote mono"><span>//</span> Protected by BruteBank. Never share your verification code.</p>
    </main>
    <script>
        (() => {
            const form = document.getElementById('verification-form');
            const status = document.getElementById('verification-status');
            const spinner = form.querySelector('.spinner');
            const checkingLabel = document.getElementById('checking-label');
            let busy = false;
            let stopped = false;
            async function check() {
                if (busy || stopped) return;
                busy = true;
                try {
                    const response = await fetch(form.action, {
                        method: 'POST', credentials: 'same-origin',
                        headers: { 'Accept': 'application/json' }, body: new FormData(form)
                    });
                    if (response.status === 401 || response.status === 419) {
                        stopped = true;
                        status.textContent = 'Your session has ended. Please sign in again.';
                        return;
                    }
                    const data = await response.json();
                    if (response.ok && data.status === 'allowed') {
                        stopped = true;
                        window.location.assign(data.redirect);
                    } else if (data.status === 'expired' || data.status === 'denied') {
                        stopped = true;
                        status.textContent = 'Verification expired or was denied. Please sign in again.';
                    } else if (!response.ok) {
                        status.textContent = 'Unable to reach BruteBank. We will retry shortly.';
                    } else {
                        status.textContent = 'Waiting for approval. Complete verification using your BruteBank email link.';
                    }
                } catch (_) {
                    status.textContent = 'Unable to check verification. We will retry shortly.';
                } finally {
                    busy = false;
                    if (stopped) {
                        spinner.hidden = true;
                        checkingLabel.textContent = 'Checks complete';
                    }
                }
            }
            form.addEventListener('submit', event => { event.preventDefault(); check(); });
            check();
            setInterval(check, 5000);
        })();
    </script>
</body>
</html>
