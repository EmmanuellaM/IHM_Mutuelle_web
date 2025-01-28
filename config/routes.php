<?php

return [
    'class' => 'app\components\RouteManager',
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
            // autres règles existantes
            'administrator/update-agape/<id:\d+>' => 'administrator/update-agape',
            'dette' => 'member/dette', // Crée une route courte "dette"
            'dettes' => 'member/dettes',
            'member/process-payment' => 'member/process-payment',
            'member/validate-mobile-payment' => 'member/validate-mobile-payment',
            'member/validate-card-payment' => 'member/validate-card-payment',
            'member/process-card-payment' => 'member/process-card-payment',
            'member/payment-success' => 'member/payment-success',
            'member/confirm-payment' => 'member/confirm-payment',
            'member/payments' => 'member/payments',
        ]
    ],
];