<?php

// Run: php tests/two-factor-unavailable.php /path/to/laravel-app
$host = rtrim($argv[1] ?? '', '/');
require $host.'/vendor/autoload.php';
require __DIR__.'/../src/Models/Settings.php';
require __DIR__.'/../src/Services/BruteBankClient.php';
require __DIR__.'/../src/Http/Middleware/RequireTwoFactor.php';
require __DIR__.'/../src/Http/Controllers/TwoFactorController.php';

use BruteBank\LaravelFilament\Http\Controllers\TwoFactorController;
use BruteBank\LaravelFilament\Http\Middleware\RequireTwoFactor;
use BruteBank\LaravelFilament\Models\Settings;
use BruteBank\LaravelFilament\Services\BruteBankClient;
use Illuminate\Http\Request;

$app = require $host.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
config([
    'database.default' => 'sqlite',
    'database.connections.sqlite.database' => ':memory:',
    'session.driver' => 'array',
]);
$app['db']->purge('sqlite');
$migration = require __DIR__.'/../database/migrations/2026_10_07_000000_create_brutebank_settings_table.php';
$migration->up();
$app['view']->replaceNamespace('brutebank', __DIR__.'/../resources/views');
$settings = Settings::current();
$settings->two_factor_enabled = true;
$settings->save();

$client = new class extends BruteBankClient {
    public bool $unavailable = true;

    public function createTwoFactor(string $user, string $email, string $ip): int
    {
        if ($this->unavailable) {
            throw new RuntimeException('Test outage: never expose this detail.');
        }

        return 123;
    }

    public function twoFactorStatus(int $id): string
    {
        if ($this->unavailable) {
            throw new RuntimeException('Test outage: never expose this detail.');
        }

        return 'allowed';
    }
};
$app->instance(BruteBankClient::class, $client);
$assert = function (bool $condition, string $message): void {
    if (! $condition) {
        throw new RuntimeException($message);
    }
};
$user = new Illuminate\Auth\GenericUser(['id' => 1, 'email' => 'test@example.com']);
$request = Request::create('https://example.com/admin?tab=security');
$request->setUserResolver(fn () => $user);
$request->setLaravelSession($app['session']->driver());
$next = function () {
    throw new RuntimeException('Unverified users must not reach protected content.');
};
$middleware = new RequireTwoFactor();
$response = $middleware->handle($request, $next);
$assert($response->getStatusCode() === 200, 'Outages must render a warning instead of a 503.');
$assert(str_contains($response->getContent(), 'Verification temporarily unavailable'), 'Warning must be visible.');
$assert(str_contains($response->getContent(), 'href="https://example.com/admin?tab=security"'), 'Retry must return to the protected page.');
$assert(! str_contains($response->getContent(), 'Test outage'), 'Exception details must not be exposed.');
$assert(str_contains($response->headers->get('Cache-Control'), 'no-store'), 'Warning must not be cached.');
$assert(! $request->session()->has('brutebank_2fa_verified_user_id'), 'Outages must not verify the user.');
$assert(! $request->session()->has('brutebank_2fa_request_id'), 'Failed creation must not create a session challenge.');

$client->unavailable = false;
$response = $middleware->handle($request, $next);
$assert($response->getStatusCode() === 302, 'Retry must resume the challenge after recovery.');
$assert($request->session()->get('brutebank_2fa_request_id') === 123, 'Retry must store the new challenge.');
$assert(! $request->session()->has('brutebank_2fa_verified_user_id'), 'Creating a challenge must not verify the user.');

$controller = new TwoFactorController();
$client->unavailable = true;
$response = $controller->verify($request);
$assert($response->getStatusCode() === 200, 'Polling outages must not return 503.');
$assert($response->getData(true)['status'] === 'unavailable', 'Polling must signal a retryable outage.');
$assert(! $request->session()->has('brutebank_2fa_verified_user_id'), 'Polling outages must not verify the user.');
$client->unavailable = false;
$response = $controller->verify($request);
$assert($response->getData(true)['status'] === 'allowed', 'Polling must recover normally.');
$assert($request->session()->get('brutebank_2fa_verified_user_id') === 1, 'Only approval may verify the user.');

echo "Two-factor outage warning, retry, and access protection checks passed.\n";
