<?php

namespace BruteBank\LaravelFilament\Filament;

use BruteBank\LaravelFilament\Filament\Pages\Settings;
use BruteBank\LaravelFilament\Http\Middleware\RequireTwoFactor;
use Filament\Contracts\Plugin;
use Filament\Panel;

class BruteBankPlugin implements Plugin
{
    public function getId(): string
    {
        return 'brutebank';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([Settings::class]);
    }

    public function boot(Panel $panel): void
    {
        // Require a second factor on authenticated Filament panel routes. The
        // middleware itself is a no-op unless two-factor verification is enabled.
        $panel->authMiddleware([RequireTwoFactor::class], isPersistent: true);
    }
}
