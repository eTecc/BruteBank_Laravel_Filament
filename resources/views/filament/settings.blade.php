<x-filament-panels::page>
    <style>
        .bb-settings{--bb-bg:#fff;--bb-text:#242331;--bb-muted:#666574;--bb-border:#e8e7ee;--bb-soft:#f8f8fb;--bb-accent:#be185d;--bb-tint:#fdf2f8;color:var(--bb-text);max-width:1120px;margin-inline:auto;width:100%}
        .dark .bb-settings{--bb-bg:#1c1c24;--bb-text:#f4f3fa;--bb-muted:#b4b2c3;--bb-border:#363541;--bb-soft:#24242e;--bb-accent:#f9a8d4;--bb-tint:#342331}
        .bb-settings *{box-sizing:border-box}
        .bb-settings .bb-banner{display:flex;align-items:center;justify-content:space-between;gap:28px;padding:32px 36px;border:1px solid var(--bb-border);border-radius:20px;background:linear-gradient(115deg,var(--bb-bg) 55%,var(--bb-tint));margin-bottom:24px}
        .bb-settings .bb-brand-link{display:inline-block;max-width:100%;border-radius:6px}.bb-settings .bb-brand-link .brand{display:block;width:200px;max-width:100%;height:auto}.bb-settings .bb-brand-link .st0{fill:var(--bb-text)}
        .bb-settings h2{font-size:24px;line-height:1.3;font-weight:700;letter-spacing:-.025em;margin:18px 0 0}.bb-settings p{color:var(--bb-muted);font-size:14px;line-height:1.7;margin:6px 0 0}
        .bb-settings .bb-visit{display:inline-flex;align-items:center;justify-content:center;gap:10px;flex-shrink:0;min-height:44px;padding:10px 16px;border:1px solid var(--bb-border);border-radius:10px;background:var(--bb-bg);color:var(--bb-text);font-size:13px;font-weight:600;text-decoration:none}.bb-settings .bb-visit:hover{border-color:var(--bb-accent)}.bb-settings .bb-visit svg{width:16px;height:16px}
        .bb-settings .bb-grid{display:grid;grid-template-columns:minmax(0,1.1fr) minmax(0,1fr);gap:24px;align-items:start}.bb-settings .bb-card{min-width:0;background:var(--bb-bg);border:1px solid var(--bb-border);border-radius:18px;padding:28px;box-shadow:0 2px 6px #00000003}
        .bb-settings h3{font-size:17px;font-weight:650;letter-spacing:-.015em;margin:0}.bb-settings .bb-eyebrow{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--bb-accent);margin-bottom:10px}
        .bb-settings .bb-field{display:block;margin-top:24px}.bb-settings .bb-label{display:block;font-size:13px;font-weight:600;margin-bottom:9px}
        .bb-settings .bb-input{display:block;width:100%;min-height:46px;border:1px solid var(--bb-border);border-radius:10px;background:var(--bb-soft);color:var(--bb-text);padding:12px 14px;font-size:14px;outline:none}.bb-settings .bb-input:focus{border-color:var(--bb-accent);box-shadow:0 0 0 3px #ec48991a}.bb-settings .bb-input::placeholder{color:var(--bb-muted)}
        .bb-settings .bb-help{font-size:12px;margin-top:8px}.bb-settings .bb-error{font-size:12px;color:var(--bb-accent);margin-top:6px}
        .bb-settings .bb-link{color:var(--bb-text);text-decoration:underline;text-underline-offset:3px}.bb-settings .bb-link:hover{color:var(--bb-accent)}
        .bb-settings .bb-option{display:flex;align-items:flex-start;justify-content:space-between;gap:22px;padding:24px 0;border-bottom:1px solid var(--bb-border);cursor:pointer}.bb-settings .bb-option:last-of-type{border-bottom:0}.bb-settings .bb-option strong{display:block;font-size:14px;font-weight:600}.bb-settings .bb-option p{font-size:13px;margin-top:5px}
        .bb-settings .bb-option input{appearance:none;-webkit-appearance:none;flex:0 0 44px;width:44px;height:26px;margin:2px 0 0;border:1px solid #9b99a8;border-radius:999px;background:#9b99a8;background-image:none;box-shadow:none;cursor:pointer;position:relative}.bb-settings .bb-option input::after{content:"";position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#fff;box-shadow:0 1px 3px #0002}.bb-settings .bb-option input:checked{background:#be185d;border-color:#be185d;background-image:none}.bb-settings .bb-option input:checked::after{transform:translateX(18px)}
        .bb-settings .bb-note{display:flex;align-items:flex-start;gap:10px;background:var(--bb-soft);border-radius:10px;padding:14px;margin-top:4px}.bb-settings .bb-note svg{width:17px;height:17px;flex-shrink:0;margin-top:2px;color:var(--bb-accent)}.bb-settings .bb-note p{font-size:12px;margin:0}
        .bb-settings .bb-footer{display:flex;justify-content:space-between;align-items:center;gap:20px;margin-top:24px;padding:20px 0 4px}.bb-settings .bb-footer p{font-size:12px;margin:0}.bb-settings .bb-save{display:inline-flex;justify-content:center;align-items:center;gap:9px;min-height:46px;border:1px solid #be185d;border-radius:10px;background:#be185d;color:white;font-size:14px;font-weight:600;padding:12px 24px;cursor:pointer;white-space:nowrap;box-shadow:0 2px 4px #be185d18}.bb-settings .bb-save svg{width:17px;height:17px}.bb-settings .bb-save:hover{background:#9d174d;border-color:#9d174d}.bb-settings .bb-save:disabled{opacity:.6;cursor:wait}
        .bb-settings a:focus-visible,.bb-settings button:focus-visible,.bb-settings .bb-option input:focus-visible{outline:3px solid var(--bb-accent);outline-offset:4px}
        @media(max-width:800px){.bb-settings .bb-grid{grid-template-columns:1fr}.bb-settings .bb-banner{padding:26px}.bb-settings .bb-card{padding:24px}}
        @media(max-width:520px){.bb-settings .bb-banner{align-items:flex-start;flex-direction:column;gap:20px;padding:22px}.bb-settings .bb-brand-link .brand{width:176px}.bb-settings .bb-card{padding:22px}.bb-settings .bb-footer{align-items:stretch;flex-direction:column-reverse;gap:16px}.bb-settings .bb-footer p{text-align:center}.bb-settings h2{font-size:22px}}
        @media(forced-colors:active){.bb-settings .bb-option input{appearance:auto;-webkit-appearance:auto}.bb-settings .bb-option input::after{display:none}}
    </style>
    <div class="bb-settings">
        <header class="bb-banner">
            <div>
                <a class="bb-brand-link" href="https://www.brutebank.io" target="_blank" rel="noopener noreferrer">@include('brutebank::logo')<span class="sr-only">(opens in a new tab)</span></a>
                <h2>A little more peace of mind.</h2>
                <p>Connect your account and choose how you protect your site.</p>
            </div>
            <a class="bb-visit" href="https://www.brutebank.io" target="_blank" rel="noopener noreferrer">Open BruteBank <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9M10 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-5"/></svg><span class="sr-only">(opens in a new tab)</span></a>
        </header>
        <form wire:submit="save">
            <div class="bb-grid">
                <section class="bb-card" aria-labelledby="bb-connection">
                    <div class="bb-eyebrow">01 &nbsp; Connect</div>
                    <h3 id="bb-connection">Your server keys</h3>
                    <p>Copy these from your server settings in BruteBank.</p>
                    <label class="bb-field" for="bb-public-key"><span class="bb-label">Public key</span>
                        <input id="bb-public-key" class="bb-input" wire:model="publicKey" autocomplete="off" spellcheck="false" placeholder="Enter your server public key" aria-describedby="bb-public-help">
                    </label>
                    <p id="bb-public-help" class="bb-help">Identifies this site in your BruteBank account.</p>
                    @error('publicKey')<p class="bb-error" role="alert">{{ $message }}</p>@enderror
                    <label class="bb-field" for="bb-secret-key"><span class="bb-label">Secret key</span>
                        <input id="bb-secret-key" class="bb-input" wire:model="secretKey" type="password" autocomplete="new-password" placeholder="Enter your server secret key" aria-describedby="bb-secret-help">
                    </label>
                    <p id="bb-secret-help" class="bb-help">Leave blank to keep your saved key. Stored encrypted and never displayed.</p>
                    @error('secretKey')<p class="bb-error" role="alert">{{ $message }}</p>@enderror
                </section>
                <section class="bb-card" aria-labelledby="bb-protection">
                    <div class="bb-eyebrow">02 &nbsp; Protect</div>
                    <h3 id="bb-protection">Protection preferences</h3>
                    <p>An extra layer of security, on your terms.</p>
                    <label class="bb-option"><span><strong>Enable protection</strong><p>Turn on the IP firewall and your selected sign-in protection. Turn off to pause protection without removing your saved keys.</p></span><input type="checkbox" wire:model="enabled" role="switch"></label>
                    @error('enabled')<p class="bb-error" role="alert">{{ $message }}</p>@enderror
                    <label class="bb-option"><span><strong>Email verification</strong><p>Add a second step to sign-in with a secure verification email.</p></span><input type="checkbox" wire:model="twoFactorEnabled" role="switch"></label>
                    @error('twoFactorEnabled')<p class="bb-error" role="alert">{{ $message }}</p>@enderror
                    <div class="bb-note"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="m12 3 8 4v5c0 5-8 9-8 9s-8-4-8-9V7l8-4Z"/><path d="m8 12 3 3 5-6"/></svg><p>Protection requires valid server keys and applies to routes protected by the plugin. Your keys and preferences are kept when protection is off. Save your changes to apply them.</p></div>
                </section>
            </div>
            <footer class="bb-footer"><p>Security, without the complexity. <a class="bb-link" href="https://www.brutebank.io" target="_blank" rel="noopener noreferrer">brutebank.io <span class="sr-only">(opens in a new tab)</span></a></p><button class="bb-save" type="submit" wire:loading.attr="disabled" wire:target="save"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg><span wire:loading.remove wire:target="save">Save changes</span><span wire:loading wire:target="save">Saving…</span></button></footer>
        </form>
    </div>
</x-filament-panels::page>
