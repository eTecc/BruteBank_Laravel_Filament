<?php

namespace BruteBank\LaravelFilament\Filament\Pages;

use BruteBank\LaravelFilament\Models\Settings as BruteBankSettings;
use BruteBank\LaravelFilament\Services\BruteBankClient;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'BruteBank';
    protected static ?string $title = 'BruteBank security';
    protected static string $view = 'brutebank::filament.settings';
    protected static ?string $navigationGroup = 'Security';

    public string $apiUrl = '';
    public string $publicKey = '';
    public string $secretKey = '';
    public bool $enabled = false;
    public bool $twoFactorEnabled = false;

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('manage-brutebank-settings');
    }

    public function mount(): void
    {
        $settings = BruteBankSettings::current();
        $this->apiUrl = (string) ($settings->api_url ?: config('brutebank.api_url'));
        $this->publicKey = (string) $settings->public_key;
        // Never hydrate the decrypted server secret into the Filament browser state.
        $this->secretKey = '';
        $this->enabled = $settings->enabled;
        $this->twoFactorEnabled = $settings->two_factor_enabled;
    }

    public function save(): void
    {
        $data = $this->validate([
            'apiUrl' => ['required', 'url'],
            'publicKey' => ['nullable', 'string', 'max:128'],
            'secretKey' => ['nullable', 'string', 'max:255'],
            'enabled' => ['boolean'],
            'twoFactorEnabled' => ['boolean'],
        ]);

        $settings = BruteBankSettings::current();
        $settings->api_url = rtrim($data['apiUrl'], '/');
        $settings->public_key = $data['publicKey'];
        if ($data['secretKey'] !== '') {
            $settings->secret_key = $data['secretKey'];
        }
        $settings->enabled = $data['enabled'] && filled($data['publicKey']) && filled($settings->secret_key);
        $settings->two_factor_enabled = $data['twoFactorEnabled'];
        $settings->save();

        app(BruteBankClient::class)->forgetBlocklist();
        Notification::make()->title('BruteBank settings saved')->success()->send();
    }
}
