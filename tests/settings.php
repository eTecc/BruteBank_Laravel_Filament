<?php

// Run: php tests/settings.php /path/to/vendor/autoload.php
require $argv[1] ?? dirname(__DIR__).'/vendor/autoload.php';
require __DIR__.'/../src/Models/Settings.php';
require __DIR__.'/../src/Filament/Pages/Settings.php';

use BruteBank\LaravelFilament\Filament\Pages\Settings as SettingsPage;
use BruteBank\LaravelFilament\Models\Settings;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

$database = new Manager();
$database->addConnection(['driver' => 'sqlite', 'database' => ':memory:']);
$database->setAsGlobal();
$database->bootEloquent();
$database->schema()->create('brutebank_settings', function (Blueprint $table): void {
    $table->id();
    $table->string('public_key')->nullable();
    $table->text('secret_key')->nullable();
    $table->boolean('enabled')->default(false);
    $table->boolean('two_factor_enabled')->default(false);
    $table->timestamps();
});

$assert = function (bool $condition, string $message): void {
    if (! $condition) {
        throw new RuntimeException($message);
    }
};

$settings = new Settings();
$assert($settings->enabled === false, 'New settings must default to disabled.');
$assert($settings->two_factor_enabled === false, 'New settings must default to two-factor disabled.');

// Mount must work on the very first request, without refreshing database defaults.
$page = new SettingsPage();
$page->mount();
$assert($page->enabled === false, 'First settings page mount must default to disabled.');
$assert($page->twoFactorEnabled === false, 'First settings page mount must default to two-factor disabled.');
$assert(Settings::count() === 1, 'Mount must create the singleton settings record.');

$settings = Settings::current();
$settings->enabled = true;
$settings->two_factor_enabled = true;
$settings->save();
$page = new SettingsPage();
$page->mount();
$assert($page->enabled === true, 'Saved enabled setting must be preserved.');
$assert($page->twoFactorEnabled === true, 'Saved two-factor setting must be preserved.');

echo "Settings defaults and first-use page mount checks passed.\n";
