<?php

return [
    /*
    |----------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |----------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */


    // 'paths' => ['*'],
    // 'allowed_methods' => ['*'],
    // 'allowed_origins' => [
    //     'http://localhost:5173',
    // ],


    // // 'allowed_origins_patterns' => [
    // //     // '/^https?:\/\/([a-z0-9-]+\.)?annaponsprojects\.com$/', // প্রোডাকশনে আনকমেন্ট করুন
    // // ],


    // 'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
    // 'exposed_headers' => [],
    // 'max_age' => 3600,
    // 'supports_credentials' => true,

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'uploads/customize'],

    'allowed_methods' => ['*'], 

    'allowed_origins' => ['http://localhost:5173', 'http://192.168.0.238:5173',"http://127.0.0.1:8000"],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Authorization'],

    'max_age' => 3600,

    'supports_credentials' => false,
];