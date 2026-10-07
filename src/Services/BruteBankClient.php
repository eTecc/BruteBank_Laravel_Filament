<?php

namespace BruteBank\LaravelFilament\Services;

use BruteBank\LaravelFilament\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BruteBankClient
{
    public function createTwoFactor(string $user, string $email, string $ip): int
    {
        $settings = $this->settings();
        if (! $settings->enabled || ! $settings->public_key || ! $settings->secret_key) {
            throw new \RuntimeException('BruteBank credentials are not configured.');
        }

        $response = $this->twoFactorRequest('post', '/api/2fa', [
            'public_key' => $settings->public_key,
            'secret_key' => $settings->secret_key,
            'user' => $user,
            'email' => $email,
            'ip_address' => $ip,
        ]);
        $id = $response->json('request.id');
        if ((int) $response->json('status') !== 1 || ! is_numeric($id) || (int) $id < 1) {
            throw new \RuntimeException('BruteBank did not create a verification request.');
        }

        return (int) $id;
    }

    public function twoFactorStatus(int $id): string
    {
        $response = $this->twoFactorRequest('get', '/api/2fa/'.$id);
        if ((int) $response->json('status') !== 1 || ! is_string($response->json('request.status'))) {
            throw new \RuntimeException('BruteBank returned an invalid verification status.');
        }

        return $response->json('request.status');
    }

    public function verifyTwoFactor(int $id, string $code): bool
    {
        $settings = $this->settings();
        $response = $this->twoFactorRequest('post', '/api/2fa/'.$id.'/verify', [
            'public_key' => $settings->public_key,
            'secret_key' => $settings->secret_key,
            'code' => $code,
        ]);

        return (int) $response->json('status') === 1 && $response->json('request.status') === 'allowed';
    }

    private function twoFactorRequest(string $method, string $path, array $payload = []): \Illuminate\Http\Client\Response
    {
        $url = rtrim(config('brutebank.api_url'), '/').$path;
        \Illuminate\Support\Facades\Log::info('BruteBank 2FA: API request', ['method' => $method, 'url' => $url]);
        try {
            $request = Http::asJson()->acceptJson()->timeout((int) config('brutebank.http_timeout', 5));
            $response = $method === 'post' ? $request->post($url, $payload) : $request->get($url);
            \Illuminate\Support\Facades\Log::info('BruteBank 2FA: API response', [
                'http_status' => $response->status(),
                'api_status' => $response->json('status'),
            ]);
            $response->throw();

            return $response;
        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::error('BruteBank 2FA: API request failed', ['exception_class' => get_class($exception)]);
            throw $exception;
        }
    }

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
            $response = Http::asJson()->timeout((int) config('brutebank.http_timeout', 5))->post(
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
            if ($response->successful()) {
                $this->forgetBlocklist();
            }
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
