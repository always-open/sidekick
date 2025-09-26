<?php

return [
    'debounced_job_maximum_debounce_milliseconds' => env('SIDEKICK_MAXIMUM_DEBOUNCE_MILLISECONDS'),
    'traits' => [
        'cache_enabled' => env('SIDEKICK_CACHE_ENABLED', true),
        'cache_ttl' => env('SIDEKICK_CACHE_TTL', 600),
    ],
];
