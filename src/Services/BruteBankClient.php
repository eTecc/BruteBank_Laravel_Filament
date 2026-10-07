<?php

namespace BruteBank\LaravelFilament\Services;

use BruteBank\LaravelFilament\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BruteBankClient
{
    public function settings(): Settings
    {
        return Settings::current();
    }

    public function isBlocked(string $ip): bool
    {
        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }

        $settings = $this->settings();
        if (! $settings->enabled || ! $settings->public_key || ! $settings->secret_key) {
            return false;
        }

        return in_array($ip, $this->blocklist($settings), true);
    }

    public function blocklist(?Settings $settings = null): array
    {
        $settings ??= $this->settings();
        if (! $settings->enabled || ! $settings->public_key || ! $settings->secret_key) {
            return [];
        }

        $key = 'brutebank:blocklist:'.hash('sha256', $settings->public_key);
        $apiUrl = rtrim(config('brutebank.api_url'), '/');

        return Cache::remember($key, now()->addSeconds(max(30, (int) config('brutebank.cache_ttl', 300))), function () use ($settings, $apiUrl) {
            try {
                $response = Http::accept('text/plain')
                    ->timeout((int) config('brutebank.http_timeout', 5))
                    ->get($apiUrl.'/api/blocks/flat_list/'.rawurlencode($settings->public_key).'/'.rawurlencode($settings->secret_key).'/1');

                if (! $response->successful()) {
                    return [];
                }

                return collect(preg_split('/\\r\\n|\\r|\\n/', trim($response->body())) ?: [])
                    ->map(fn ($ip) => trim($ip))
                    ->filter(fn ($ip) => filter_var($ip, FILTER_VALIDATE_IP))
                    ->unique()
                    ->values()
                    ->all();
            } catch (\Throwable) {
                return [];
            }
        });
    }

    public function reportFailure(string $ip, string $username, string $type = 'laravel'): void
    {
        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            return;
        }

        $settings = $this->settings();
        if (! $settings->enabled || ! $settings->public_key || ! $settings->secret_key) {
            return;
        }

        try {
            Http::asJson()->timeout((int) config('brutebank.http_timeout', 5))->post(
                rtrim(config('brutebank.api_url'), '/').'/api/log',
                [
                    'public_key' => $settings->public_key,
                    'secret_key' => $settings->secret_key,
                    'objects' => [[
                        'type' => $type,
                        'ip_address' => $ip,
                        'localUser' => mb_substr($username, 0, 255),
                        'date' => (string) now()->timestamp,
                    ]],
                ]
            );
        } catch (\Throwable) {
            // Security reporting must not break the host site's authentication flow.
        }
    }

    public function sendHeartbeat(): void
    {
        $settings = $this->settings();
        if (! $settings->enabled || ! $settings->public_key || ! $settings->secret_key) {
            return;
        }

        try {
            Http::asJson()->timeout((int) config('brutebank.http_timeout', 5))->post(
                rtrim(config('brutebank.api_url'), '/').'/api/server/heartbeat',
                [
                    'public_key' => $settings->public_key,
                    'secret_key' => $settings->secret_key,
                    'plugin_version' => config('brutebank.plugin_version'),
                ]
            );
        } catch (\Throwable) {
            // Heartbeat failures do not affect requests on the protected site.
        }
    }

    public function forgetBlocklist(): void
    {
        $settings = $this->settings();
        Cache::forget('brutebank:blocklist:'.hash('sha256', $settings->public_key));
    }
}
