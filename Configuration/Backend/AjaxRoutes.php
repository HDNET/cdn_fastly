<?php

declare(strict_types=1);

use HDNET\CdnFastly\Controller\ClearCacheController;

return [
    'fastly' => [
        'path' => '/backend/fastly',
        'target' => ClearCacheController::class,
    ],
];
