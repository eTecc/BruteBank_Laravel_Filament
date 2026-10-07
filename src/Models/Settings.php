<?php

namespace BruteBank\LaravelFilament\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Settings extends Model
{
    protected $table = 'brutebank_settings';

    protected $fillable = ['api_url', 'public_key', 'secret_key', 'enabled', 'two_factor_enabled'];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'two_factor_enabled' => 'boolean',
        ];
    }

    public function getSecretKeyAttribute(?string $value): string
    {
        if (! $value) {
            return '';
        }

        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return '';
        }
    }

    public function setSecretKeyAttribute(?string $value): void
    {
        $this->attributes['secret_key'] = filled($value) ? Crypt::encryptString($value) : null;
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(['id' => 1]);
    }
}
