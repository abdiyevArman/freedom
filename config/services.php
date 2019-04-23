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

    'facebook_poster' => [
        'app_id' => '199736437404963',
        'app_secret' => '5d23622d330cf4a8efe841a034c6ba07',
        'access_token' => 'EAAC1qMNmdSMBAMwVbg4F0tux38UvbQMfu342QUYkbA5vmuUJTXXO12YtJLiog1vihwbyhZCmKa0H8FyZCg6qEaQZBFHUB9q8McseHi4CucfSLvSundCUzXUxVmsu5qm5S698stsAnx8YfxdehjEyb4tqKzxetjaBf75AZBKgVouxWhNkewRLpQmkRvGB9KUgv4ZBEhEYZCBgZDZD',
    ],

];
