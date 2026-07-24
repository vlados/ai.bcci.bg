<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // OpenPanel (openpanel.dev) — privacy-friendly, cookieless web analytics.
    // The client id is a public, browser-exposed identifier (not a secret), so
    // it ships to every visitor; keeping it here just lets a self-hosted or
    // preview instance override it. `enabled` is null by default, which means
    // "only on the canonical production host" (see partials/analytics) so a dev
    // box or preview build never feeds the real project; set it true/false to
    // force it on (e.g. to watch beacons locally) or off.
    'openpanel' => [
        'client_id' => env('OPENPANEL_CLIENT_ID', '031983ff-24e0-4764-8cdf-8db97880de91'),
        'api_url' => env('OPENPANEL_API_URL', 'https://stats.vladko.dev/api'),
        'script_url' => env('OPENPANEL_SCRIPT_URL', 'https://openpanel.dev/op1.js'),
        'enabled' => env('OPENPANEL_ENABLED'),
    ],

];
