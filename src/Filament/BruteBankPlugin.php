<?php

namespace BruteBank\LaravelFilament\Filament;

use BruteBank\LaravelFilament\Filament\Pages\Settings;
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
        // The package routes and middleware are registered by the service provider.
    }
}
