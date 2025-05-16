<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // Only your API routes and sanctum cookie paths will handle CORS
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Allow all HTTP methods for those routes
    'allowed_methods' => ['*'],

    // List all allowed frontend origins here, including ports if any
    'allowed_origins' => [
        'http://localhost',           // frontend on default port 80
        'http://localhost:3000',      // example if your frontend runs on 3000
        'http://127.0.0.1:8000',      // your backend API origin
    ],

    // No patterns needed here
    'allowed_origins_patterns' => [],

    // Allow all headers
    'allowed_headers' => ['*'],

    // No exposed headers
    'exposed_headers' => [],

    'max_age' => 0,

    // Set to false unless you need cookies/auth credentials across origins
    'supports_credentials' => false,

];
