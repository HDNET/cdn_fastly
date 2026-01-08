<?php

use HDNET\CdnFastly\Cache\FastlyBackend;

defined('TYPO3') || die();

$boot = static function (): void {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'] ??= [];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly']['backend'] ??= FastlyBackend::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly']['groups'] ??= [
        'fastly',
    ];
};

$boot();
unset($boot);
