<?php

// Run against a Composer installation containing any supported Filament major:
// php tests/compatibility.php /path/to/vendor/autoload.php
require $argv[1] ?? dirname(__DIR__) . '/vendor/autoload.php';

use BruteBank\LaravelFilament\Filament\BruteBankPlugin;
use BruteBank\LaravelFilament\Filament\Pages\Settings;
use Filament\Contracts\Plugin;

function check(bool $condition, string $message): void
{
    if (! $condition) {
        throw new RuntimeException($message);
    }
}

$page = new Settings();
check($page->getView() === 'brutebank::filament.settings', 'Settings view mismatch');
check(Settings::getNavigationIcon() === 'heroicon-o-shield-check', 'Navigation icon mismatch');
check(Settings::getNavigationGroup() === 'Security', 'Navigation group mismatch');
check(Settings::getNavigationLabel() === 'BruteBank', 'Navigation label mismatch');
check((new BruteBankPlugin()) instanceof Plugin, 'Plugin contract mismatch');
check((new BruteBankPlugin())->getId() === 'brutebank', 'Plugin ID mismatch');

echo "Filament settings and plugin compatibility passed.\n";
