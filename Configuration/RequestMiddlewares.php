<?php

use HDNET\CdnFastly\Middleware\FastlyMiddleware;

return [
    'frontend' => [
        'site/fastly' => [
            'target' => FastlyMiddleware::class,
            'after' => [
                'typo3/cms-frontend/authentication'
            ]
        ]
    ],
];
