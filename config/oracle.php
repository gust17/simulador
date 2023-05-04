<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DBORACLE_TNS', ''),
        'host'           => env('DBORACLE_HOST', ''),
        'port'           => env('DBORACLE_PORT', '1521'),
        'database'       => env('DBORACLE_DATABASE', ''),
        'service_name'   => env('DBORACLE_SERVICE_NAME', ''),
        'username'       => env('DBORACLE_USERNAME', ''),
        'password'       => env('DBORACLE_PASSWORD', ''),
        'charset'        => env('DBORACLE_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DBORACLE_PREFIX', ''),
        'prefix_schema'  => env('DBORACLE_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
        'load_balance'   => env('DB_LOAD_BALANCE', 'yes'),
        'dynamic'        => [],
    ],
    'sessionVars' => [
        'NLS_TIME_FORMAT'         => 'HH24:MI:SS',
        'NLS_DATE_FORMAT'         => 'YYYY-MM-DD HH24:MI:SS',
        'NLS_TIMESTAMP_FORMAT'    => 'YYYY-MM-DD HH24:MI:SS',
        'NLS_TIMESTAMP_TZ_FORMAT' => 'YYYY-MM-DD HH24:MI:SS TZH:TZM',
        'NLS_NUMERIC_CHARACTERS'  => '.,',
    ],
];
