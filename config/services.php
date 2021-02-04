<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'facebook' =>[
        'client_id'=>'412054205897803',
        'client_secret' => 'b7acb2fc2a79d27fff713e2d25147f61',
        'redirect' => 'https://shana.co.in/login/facebook/callback',
    ],
    /*'google' =>[
        'client_id'=>'487692563717-8v7hp9ofo2g5pamqqe47sh9uebvcjlvd.apps.googleusercontent.com',
        'client_secret' => 'crpk7VfOBUraqlxB5Zd19W0D',
        'redirect' => 'https://shana.co.in/login/google/callback',
    ],*/
    'google' =>[
        'client_id'=>'631504057795-e171q6o589motn9vfuqmfa10u1egedjj.apps.googleusercontent.com',
        'client_secret' => 'MxgTakgMN4-wTlIu-FLVsTUc',
        'redirect' => 'https://shana.co.in/login/google/callback',
    ],

];
