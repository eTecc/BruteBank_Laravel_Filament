<?php

namespace BruteBank\LaravelFilament;

use BruteBank\LaravelFilament\Http\Middleware\EnforceBlocklist;
use BruteBank\LaravelFilament\Http\Middleware\RequireTwoFactor;
use BruteBank\LaravelFilament\Listeners\RecordFailedLogin;
use BruteBank\LaravelFilament\Listeners\ResetTwoFactorSession;
use BruteBank\LaravelFilament\Services\BruteBankClient;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\ServiceProvider;

class BruteBankServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/brutebank.php', 'brutebank');
        $this->app->singleton(BruteBankClient::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/brutebank.php' => config_path('brutebank.php'),
        ], 'brutebank-config');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'brutebank');

        Route::middleware('web')->group(__DIR__.'/../routes/web.php');
        Event::listen(Failed::class, RecordFailedLogin::class);
        Event::listen(Login::class, ResetTwoFactorSession::class);

        Schedule::call(fn () => app(BruteBankClient::class)->sendHeartbeat())
            ->everyFifteenMinutes()
            ->name('brutebank-heartbeat')
            ->withoutOverlapping();

        $this->app['router']->aliasMiddleware('brutebank.blocklist', EnforceBlocklist::class);
        $this->app['router']->aliasMiddleware('brutebank.2fa', RequireTwoFactor::class);
    }
}
