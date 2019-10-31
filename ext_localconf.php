<?php

defined('TYPO3_MODE') || die();

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = \Pavel\CdnFastly\Hooks\FastlyClearCache::class . '->FastlyClearCache';

$registry->registerIcon('extension-cdn_fastly-clearcache', \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class, [
    'source' => 'EXT:cdn_fastly/Resources/Public/Icons/Cache/FastlyClearCache.png',
]);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'] = [
    'backend' => Pavel\CdnFastly\Cache\FastlyBackend::class,
    'groups' => [
        'fastly',
        'pages',
        'news',
    ],
];
