<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Agora App Credentials
    |--------------------------------------------------------------------------
    |
    | App ID is public and should be configured on the frontend once.
    | App Certificate must stay server-side only — used to sign RTC tokens.
    |
    */

    'app_id' => env('AGORA_APP_ID'),

    'app_certificate' => env('AGORA_APP_CERTIFICATE'),

    /*
    |--------------------------------------------------------------------------
    | RTC Token TTL (seconds)
    |--------------------------------------------------------------------------
    */

    'token_ttl_seconds' => (int) env('AGORA_TOKEN_TTL_SECONDS', 3600),

];
