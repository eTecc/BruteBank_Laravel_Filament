<?php

return [
    'api_url' => 'https://www.brutebank.io',
    'cache_ttl' => (int) env('BRUTEBANK_CACHE_TTL', 300),
    'http_timeout' => (int) env('BRUTEBANK_HTTP_TIMEOUT', 5),
    'plugin_version' => '0.1.0',
    'challenge_ttl' => (int) env('BRUTEBANK_2FA_TTL', 240),
];
