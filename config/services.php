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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'naver' => [
        'client_id' => env('NAVER_CLIENT_ID'),
        'client_secret' => env('NAVER_CLIENT_SECRET'),
        'redirect' => env('NAVER_REDIRECT_URI')
    ],

    'naverCustom' => [
        'client_id' => env('NAVER_CLIENT_ID', "duaxYdtz0yxSw8K3O7HT"),
        'client_secret' => env('NAVER_CLIENT_SECRET', "SP6lZUNGw1"),
        'redirect' => env('NAVER_REDIRECT_URI',"/login/naverCustom")
    ],

    'kakao' => [
        'client_id' => env('KAKAO_CLIENT_ID'),
        'api_key' => env('KAKAO_API_KEY', '0f687fc52063d0d59a96eabc52153e2d'),
        'client_secret' => env('KAKAO_CLIENT_SECRET'),
        'redirect' => env('KAKAO_REDIRECT_URI', )
    ],

    'kakaoCustom' => [
        'client_id' => env('KAKAO_CLIENT_ID', "382ea2610142c4e2c16534aa66204092"),
        'client_secret' => env('KAKAO_CLIENT_SECRET', ""),
        'redirect' => env('KAKAO_REDIRECT_URI', config('app.url')."/login/kakaoCustom")
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', "403882026735-m2q51uifg8tut2id9a5fkpihuf3q2dfq.apps.googleusercontent.com"),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', "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"),
        'redirect' => env('GOOGLE_REDIRECT_URI', "/login/google"),
        'api_key' => env('GOOGLE_API_KEY', 'AIzaSyCWeskwgb6rBDqs15-s31FwgExpBmAUL-w'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', "2502926093182098"),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', "36c4428d72f72867945d4d31a6f2fde6"),
        'redirect' => env('FACEBOOK_REDIRECT_URI', "/login/facebook"),
    ],

    'business' => [
        'client_id' => env('BUSINESS_CLIENT_ID', "Zhm2Ui"),
    ],

    'gong_gong' => [
        'key' => env('GONG_GONG_KEY', 'sQFVwueQbqWZjMG0EzzVt3B0m04Fk2uSdJgzkiOb77GIMzvfJUicNcv9jmgcikJJ4cxxwTKsy2EsS1M1E1gKJg%3D%3D')
    ]
];
