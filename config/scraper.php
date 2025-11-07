<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Blocked User Agents
    |--------------------------------------------------------------------------
    |
    | Requests whose User-Agent header contains any of these substrings will be
    | rejected immediately with a 403 status. Keep the list focused to avoid
    | blocking legitimate traffic. All comparisons are case-insensitive.
    */
    'blocked_user_agents' => [
        'curl',
        'wget',
        'python-requests',
        'httpclient',
        'scrapy',
        'java/',
        'libwww-perl',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Content Types
    |--------------------------------------------------------------------------
    |
    | Requests whose Accept header does not contain at least one of these
    | substrings will be flagged as suspicious.
    */
    'allowed_content_types' => [
        'text/html',
        'application/xhtml+xml',
        'application/json',
    ],

    /*
    |--------------------------------------------------------------------------
    | Require Accept-Language Header
    |--------------------------------------------------------------------------
    */
    'require_accept_language' => true,

    /*
    |--------------------------------------------------------------------------
    | Suspicious Attempt Threshold
    |--------------------------------------------------------------------------
    |
    | When a request is flagged as suspicious it will count towards this limit.
    | Exceeding the limit within the decay window triggers a 403 response.
    */
    'allowed_suspicious_attempts' => 3,
    'decay_seconds' => 600, // 10 minutes
];


