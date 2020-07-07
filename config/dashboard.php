<?php

return [
    /*
     * The dashboard supports these themes:
     *
     * - light: always use light mode
     * - dark: always use dark mode
     * - device: follow the OS preference for determining light or dark mode
     * - auto: use light mode when the sun is up, dark mode when the sun is down
     */
    'theme' => 'light',

    /*
     * When the dashboard uses the `auto` theme, these coordinates will be used
     * to determine whether the sun is up or down
     */
    'auto_theme_location' => [
        'lat' => 51.260197,
        'lng' => 4.402771,
    ],

    'tiles' => [
        'time_weather' => [
            'open_weather_map_key' => env('OPEN_WEATHER_MAP_KEY'),
            'open_weather_map_city' => 'Perugia',
            'units' => 'metric', // 'metric' or 'imperial' (metric is default)
            'buienradar_latitude' => env('BUIENRADAR_LATITUDE'),
            'buienradar_longitude' => env('BUIENRADAR_LONGITUDE'),
        ],
        'forge' => [
            'token' => env('FORGE_API_TOKEN'),
            'servers' => [
                'refresh_interval_in_seconds' => 3600
            ],
            'recent_events' => [
                'refresh_interval_in_seconds' => 60
            ],
        ],
        'accuweather' => [
            'location_key' => env('ACCUWEATHER_LOCATION', '216438'),
            'api_key' => env('ACCUWEATHER_API_KEY'),
            'system' => 'Metric',
            'date_format' => 'd/m',
        ],
        'charts' => [
            'refresh_interval_in_seconds' => 60*5,
        ],

    ],
];
