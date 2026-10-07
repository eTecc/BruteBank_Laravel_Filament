# BruteBank Laravel + Filament

Installable Composer package for Laravel 13 and Filament 5. It provides a Filament settings page, BruteBank blocklist enforcement, failed-login reporting, heartbeat reporting, and optional email one-time-code verification.

## Install from a private GitHub repository

This package is not on Packagist. Add the private Git repository to the customer application's Composer configuration (replace the URL with the actual repository URL):

```sh
composer config repositories.brutebank vcs git@github.com:YOUR_ORG/BruteBank_Laravel_Filament.git
```

Make sure the machine running Composer has read access to that repository, such as an SSH deploy key. For a tagged release, install the matching version:

```sh
composer require brutebank/laravel-filament:^0.1
```

For an untagged development branch, use:

```sh
composer require brutebank/laravel-filament:dev-main
```

The package requires PHP 8.4+, Laravel 13, and Filament 5. Composer's Laravel package discovery registers its service provider. Then run the package migration:

```sh
php artisan migrate
```

For local development, configure a Composer `path` repository pointing to this checkout instead of the private Git repository.

In a Filament panel provider, register the plugin:

```php
use BruteBank\LaravelFilament\Filament\BruteBankPlugin;

->plugin(new BruteBankPlugin())
```

Grant the `manage-brutebank-settings` ability to trusted administrators (for example, define it with a Gate in `AppServiceProvider`). Settings-page access is denied unless this ability returns true.

## Protect routes

The package registers `brutebank.blocklist` and `brutebank.2fa` middleware aliases. Apply them after authentication to routes that should be protected; for example:

```php
Route::middleware(['auth', 'brutebank.blocklist', 'brutebank.2fa'])->group(function () {
    Route::get('/account', AccountController::class);
});
```

The blocklist is fetched from BruteBank and cached for five minutes by default. Failed login attempts are sent to `/api/log`. The optional second factor mails a six-digit code to the signed-in user's email; the protected route group remains inaccessible until verification. Configure the host app's trusted proxies correctly so `Request::ip()` resolves the actual client IP.

Add the host app's scheduler to cron for the 15-minute server heartbeat, as with other Laravel scheduled tasks:

```cron
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

The settings page supports the API URL and BruteBank public/secret key. The secret is encrypted at rest using Laravel's application key. `BRUTEBANK_API_URL`, `BRUTEBANK_CACHE_TTL`, `BRUTEBANK_HTTP_TIMEOUT`, and `BRUTEBANK_2FA_TTL` provide defaults. CAPTCHA and third-party form auditing are not included in this first release.
