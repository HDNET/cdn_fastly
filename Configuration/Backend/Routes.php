<?php

use Pavel\CdnFastly\Hooks\FastlyClearCache;

return [
    'fastly' => [
        'path' => '/backend/fastly',
        'target' => FastlyClearCache::class . '::clear',
    ],
];
