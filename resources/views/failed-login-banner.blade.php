@if (session('brutebank_failed_login'))
    <div role="alert" style="padding:16px;border:2px solid #191919;background:#efebe4;color:#191919;box-shadow:4px 4px 0 #ff3b5c;margin-bottom:20px;">
        <div style="display:flex;align-items:flex-start;gap:12px;">
            <svg aria-hidden="true" viewBox="0 0 32 32" fill="none" style="width:28px;height:28px;flex-shrink:0;color:#ff3b5c;">
                <path d="M16 3 27 7v8c0 7-4.7 11.5-11 14C9.7 26.5 5 22 5 15V7l11-4Z" stroke="currentColor" stroke-width="2.2"/>
                <path d="M16 10v8m0 4v1" stroke="currentColor" stroke-width="2.2"/>
            </svg>
            <div>
                <strong style="display:block;font-family:ui-monospace,monospace;font-size:12px;line-height:1.6;text-transform:uppercase;">Your sign-in attempt has been logged by BruteBank.</strong>
                <p style="margin:6px 0 0;font-size:13px;line-height:1.6;">This failed sign-in attempt has been recorded for security monitoring.</p>
            </div>
        </div>
    </div>
@endif
