<x-filament-panels::page>
    <style>
        .bb-settings{--bb-bg:#fff;--bb-text:#111827;--bb-muted:#6b7280;--bb-border:#e5e7eb;--bb-soft:#f9fafb;color:var(--bb-text)}
        .dark .bb-settings{--bb-bg:#18181b;--bb-text:#f3f4f6;--bb-muted:#a1a1aa;--bb-border:#3f3f46;--bb-soft:#27272a}
        .bb-settings *{box-sizing:border-box}.bb-settings .bb-banner{display:flex;align-items:center;gap:18px;padding:24px;border:1px solid var(--bb-border);border-radius:16px;background:var(--bb-bg);border-top:4px solid #ec4899;margin-bottom:24px}
        .bb-settings .bb-icon{display:grid;place-items:center;flex-shrink:0;width:52px;height:52px;border-radius:12px;background:#fdf2f8;color:#db2777}.bb-settings .bb-icon svg{width:28px;height:28px}
        .bb-settings h2{font-size:22px;font-weight:800;margin:0}.bb-settings p{color:var(--bb-muted);font-size:14px;line-height:1.6;margin:6px 0 0}
        .bb-settings .bb-grid{display:grid;grid-template-columns:minmax(0,1.2fr) minmax(0,1fr);gap:24px}.bb-settings .bb-card{background:var(--bb-bg);border:1px solid var(--bb-border);border-radius:14px;padding:24px;box-shadow:0 1px 3px #00000008}
        .bb-settings h3{font-size:16px;font-weight:700;margin:0}.bb-settings .bb-eyebrow{font:11px ui-monospace,monospace;text-transform:uppercase;letter-spacing:.1em;color:#db2777;margin-bottom:8px}
        .bb-settings .bb-field{display:block;margin-top:22px}.bb-settings .bb-label{display:block;font-size:14px;font-weight:600;margin-bottom:8px}
        .bb-settings .bb-input{display:block;width:100%;border:1px solid var(--bb-border);border-radius:8px;background:var(--bb-soft);color:var(--bb-text);padding:12px 14px;font-size:14px;outline:none}.bb-settings .bb-input:focus{border-color:#ec4899;box-shadow:0 0 0 3px #ec489920}.bb-settings .bb-input::placeholder{color:var(--bb-muted)}
        .bb-settings .bb-help{font-size:12px}.bb-settings .bb-error{font-size:12px;color:#e11d48;margin-top:6px}
        .bb-settings .bb-option{display:flex;align-items:flex-start;gap:14px;padding:20px 0;border-bottom:1px solid var(--bb-border);cursor:pointer}.bb-settings .bb-option:last-child{border-bottom:0;padding-bottom:0}.bb-settings .bb-option input{margin-top:3px;width:20px;height:20px;accent-color:#ec4899;flex-shrink:0}.bb-settings .bb-option strong{font-size:14px;font-weight:600}.bb-settings .bb-option p{font-size:13px}
        .bb-settings .bb-footer{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-top:24px}.bb-settings .bb-footer p{font-size:12px;margin:0}.bb-settings .bb-save{border:0;border-radius:8px;background:#ec4899;color:white;font-size:14px;font-weight:600;padding:12px 22px;cursor:pointer;white-space:nowrap}.bb-settings .bb-save:hover{background:#db2777}.bb-settings .bb-save:focus-visible{outline:3px solid #f9a8d4;outline-offset:3px}.bb-settings .bb-save:disabled{opacity:.6;cursor:wait}
        @media(max-width:760px){.bb-settings .bb-grid{grid-template-columns:1fr}.bb-settings .bb-banner,.bb-settings .bb-card{padding:20px}.bb-settings .bb-footer{align-items:flex-start;flex-direction:column}}
    </style>
    <div class="bb-settings">
        <header class="bb-banner">
            <div class="bb-icon" aria-hidden="true"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M12 3l9 4v5c0 5-9 9-9 9s-9-4-9-9V7l9-4z"/></svg></div>
            <div><h2>BruteBank</h2><p>Connect your site. Keep unwanted visitors out.</p></div>
        </header>
        <form wire:submit="save">
            <div class="bb-grid">
                <section class="bb-card" aria-labelledby="bb-connection">
                    <div class="bb-eyebrow">01 / Connection</div>
                    <h3 id="bb-connection">Server credentials</h3>
                    <p>Use the keys from your server in BruteBank.</p>
                    <label class="bb-field" for="bb-public-key"><span class="bb-label">Public key</span>
                        <input id="bb-public-key" class="bb-input" wire:model="publicKey" autocomplete="off" spellcheck="false" placeholder="Enter your server public key" aria-describedby="bb-public-help">
                    </label>
                    <p id="bb-public-help" class="bb-help">Identifies this site in your BruteBank account.</p>
                    @error('publicKey')<p class="bb-error" role="alert">{{ $message }}</p>@enderror
                    <label class="bb-field" for="bb-secret-key"><span class="bb-label">Secret key</span>
                        <input id="bb-secret-key" class="bb-input" wire:model="secretKey" type="password" autocomplete="new-password" placeholder="Enter a key, or leave blank to keep the saved key" aria-describedby="bb-secret-help">
                    </label>
                    <p id="bb-secret-help" class="bb-help">Encrypted at rest. Your saved secret is never displayed here.</p>
                    @error('secretKey')<p class="bb-error" role="alert">{{ $message }}</p>@enderror
                </section>
                <section class="bb-card" aria-labelledby="bb-protection">
                    <div class="bb-eyebrow">02 / Protection</div>
                    <h3 id="bb-protection">Security controls</h3>
                    <p>Choose the protections for your configured routes.</p>
                    <label class="bb-option"><input type="checkbox" wire:model="enabled"><span><strong>IP firewall</strong><p>Enforce your BruteBank blocklist and report failed login attempts. Requires valid server keys.</p></span></label>
                    <label class="bb-option"><input type="checkbox" wire:model="twoFactorEnabled"><span><strong>Email two-factor verification</strong><p>Require an email verification code before accessing routes protected by the 2FA middleware.</p></span></label>
                </section>
            </div>
            <footer class="bb-footer"><p>Connected service: https://brutebank.io</p><button class="bb-save" type="submit" wire:loading.attr="disabled" wire:target="save"><span wire:loading.remove wire:target="save">Save settings</span><span wire:loading wire:target="save">Saving…</span></button></footer>
        </form>
    </div>
</x-filament-panels::page>
