<?php

// Run: php tests/heartbeat.php /path/to/laravel-app
$host = rtrim($argv[1] ?? '', '/');
require $host.'/vendor/autoload.php';
require __DIR__.'/../src/Services/BruteBankClient.php';
require __DIR__.'/../src/Http/Middleware/SendHeartbeat.php';
$app = require $host.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
config(['cache.default' => 'array', 'brutebank.api_url' => 'https://www.brutebank.io']);

$settings = new BruteBank\LaravelFilament\Models\Settings();
$settings->enabled = true;
$settings->public_key = 'heartbeat-test-public';
$settings->secret_key = 'heartbeat-test-secret';
$client = new class($settings) extends BruteBank\LaravelFilament\Services\BruteBankClient {
    public function __construct(private BruteBank\LaravelFilament\Models\Settings $testSettings) {}
    public function settings(): BruteBank\LaravelFilament\Models\Settings
    {
        return $this->testSettings;
    }
};
$app->instance(BruteBank\LaravelFilament\Services\BruteBankClient::class, $client);
$assert = function (bool $condition, string $message): void {
    if (! $condition) {
        throw new RuntimeException($message);
    }
};
$url = 'https://www.brutebank.io/api/server/heartbeat';
Illuminate\Support\Facades\Http::preventStrayRequests();
Illuminate\Support\Facades\Http::fake([$url => Illuminate\Support\Facades\Http::response(['status' => 1])]);
$middleware = new BruteBank\LaravelFilament\Http\Middleware\SendHeartbeat();
$request = Illuminate\Http\Request::create('/admin/login');
$response = $middleware->handle($request, fn () => new Symfony\Component\HttpFoundation\Response('ok'));
$assert(Illuminate\Support\Facades\Http::recorded()->count() === 0, 'Heartbeat must run after the response.');
$middleware->terminate($request, $response);
$client->sendHeartbeat();
$recorded = Illuminate\Support\Facades\Http::recorded();
$assert($recorded->count() === 1, 'Requests and scheduler must share the throttle.');
$assert($recorded[0][0]['public_key'] === $settings->public_key, 'Heartbeat must send configured credentials.');
$assert(! empty($recorded[0][0]['plugin_version']), 'Heartbeat must send the package version.');
Illuminate\Support\Carbon::setTestNow(now()->addMinutes(16));
$client->sendHeartbeat();
$assert(Illuminate\Support\Facades\Http::recorded()->count() === 2, 'Heartbeat must resume after 15 minutes.');
$settings->enabled = false;
Illuminate\Support\Carbon::setTestNow(now()->addMinutes(16));
$client->sendHeartbeat();
$assert(Illuminate\Support\Facades\Http::recorded()->count() === 2, 'Disabled integration must not send.');
$settings->enabled = true;
Illuminate\Support\Facades\Http::swap(new Illuminate\Http\Client\Factory());
Illuminate\Support\Facades\Http::preventStrayRequests();
Illuminate\Support\Facades\Http::fake([$url => Illuminate\Support\Facades\Http::response([], 503)]);
$client->sendHeartbeat();
$client->sendHeartbeat();
$assert(Illuminate\Support\Facades\Http::recorded()->count() === 1, 'Failures must be throttled.');
Illuminate\Support\Carbon::setTestNow(now()->addSeconds(61));
$client->sendHeartbeat();
$assert(Illuminate\Support\Facades\Http::recorded()->count() === 2, 'Failures must retry after one minute.');
Illuminate\Support\Carbon::setTestNow();
echo "Heartbeat checks passed.\n";
