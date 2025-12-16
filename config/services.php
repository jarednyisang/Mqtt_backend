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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'africastalking' => [
        'username' => env('AFRICASTALKING_USERNAME', 'sandbox'),
        'api_key' => env('AFRICASTALKING_API_KEY'),
        'from' => env('AFRICASTALKING_FROM', null), // Optional sender ID
    ],

    'mpesa' => [
      'consumer_key' => env('MPESA_CONSUMER_KEY', ''),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),
    'passkey' => env('MPESA_PASSKEY', ''),
    'shortcode' => env('MPESA_SHORTCODE', ''),
    'test_msisdn' => env('MPESA_TEST_MSISDN', '254708374149'),
    'test_amount' => env('MPESA_TEST_AMOUNT', '1'),
    
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),
    
    'sandbox' => [
        'endpoint' => 'https://sandbox.safaricom.co.ke',
        'register_url' => '/mpesa/c2b/v1/registerurl',
        'simulate_transaction' => '/mpesa/c2b/v1/simulate',
    ],
    
    'production' => [
        'endpoint' => 'https://api.safaricom.co.ke',
        'register_url' => '/mpesa/c2b/v1/registerurl',
    ],
    
    'callback_url' => env('MPESA_CALLBACK_URL', 'https://yourdomain.com/api/mpesa/callback'),
    'validation_url' => env('MPESA_VALIDATION_URL', 'https://yourdomain.com/api/mpesa/validate'),
    'confirmation_url' => env('MPESA_CONFIRMATION_URL', 'https://yourdomain.com/api/mpesa/confirm'),
    'timeout_url' => env('MPESA_TIMEOUT_URL', 'https://yourdomain.com/api/mpesa/timeout'),
    'result_url' => env('MPESA_RESULT_URL', 'https://yourdomain.com/api/mpesa/result'),
],

'lemonsqueezy' => [
    'api_key' => env('LEMON_SQUEEZY_API_KEY'),
    'signing_secret' => env('LEMON_SQUEEZY_SIGNING_SECRET'),
    'store_id' => env('LEMON_SQUEEZY_STORE_ID'),
    'product_id' => env('LEMON_SQUEEZY_PRODUCT_ID'),
    'variant_id' => env('LEMON_SQUEEZY_VARIANT_ID'),
],


];
