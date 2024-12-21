<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode'    => env('PAYPAL_MODE', 'live'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', 'AQnFebF_QrN-Lft-mbYhWO3kZ6u3fuNH1JIu0kO7qlbG3FHvutM2SwY_jUPiuy_hX5hd0jQVwTp7JMHq'),
        'client_secret'     => env('PAYPAL_SANDBOX_CLIENT_SECRET', 'EJ9iYp5_DdMdw0OXlyRHfpqcDhy5L61XitDhA4dlm_19d4-HB6ojhOEcWb5M-OVgLM5Kx4rS-5wluEqG'),
        'app_id'            => 'APP-80W284485P519543T',
    ],
    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', 'ARRkU3m6lSwJ68CGoyqiJBfT95EQUDWrb4RAHW8cMkY4DEeiS5OjuQG9loRoUHd1gxP4sgUpbwhkhu5Y'),
        'client_secret'     => env('PAYPAL_LIVE_CLIENT_SECRET', 'ELhie3QHlACqRAFiZR_qlCadCF99vj0UkqjfGCwj9dcCo_FUYlQ7D4-M-hdKvsHo36VXpHyUzCWsk8Ea'),
        'app_id'            => env('PAYPAL_LIVE_APP_ID', ''),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Order'), // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => env('PAYPAL_CURRENCY', 'USD'),
    'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
    'locale'         => env('PAYPAL_LOCALE', 'en_US'), // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', true), // Validate SSL when creating api client.
];
