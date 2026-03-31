<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Token Hash
    |--------------------------------------------------------------------------
    |
    | Base64-encoded bcrypt hash of the API token used for authentication.
    | Generate it with: php artisan clavis:token
    |
    | This value must be kept secret, similar to APP_KEY.
    |
    */

    'hash' => env('CLAVIS_HASH'),

];
