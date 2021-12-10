<?php

return [
    'version' => env('APP_VERSION', '1.0.0-') . '@@version@@',

    'npm' =>env('NPM_PATH'),
    'node' =>env('NODE_PATH'),

    'frontend_url' => env('FRONTEND_URL', env('APP_URL')),

    'user_email_verify_endpoint' => '%s/#/verify/%s',
    'user_reset_password_endpoint' => '%s/#/reset/%s',
    'user_social_login_endpoint' => '%s/#/signin/social/%s',

    'logs' => [
        'http' => env('LOG_ALL_HTTP_REQUEST', true),
        'queries' => env('LOG_ALL_QUERIES', true),
        'queries_timeout' => env('LOG_QUERIES_TIMEOUT', 20),
    ],
];
