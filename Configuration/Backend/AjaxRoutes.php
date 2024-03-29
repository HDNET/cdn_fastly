<?php

declare(strict_types=1);

use HDNET\CdnFastly\Hooks\FastlyClearCache;

return [
    'fastly' => [
        'path' => '/backend/fastly',
        // Use BE route direclty as Request-response call
        'target' => FastlyClearCache::class . '::clear',
    ],
];
