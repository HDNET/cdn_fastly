<?php

use HDNET\CdnFastly\Cache\FastlyBackend;
use HDNET\CdnFastly\Hooks\FastlyClearCache;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Container\Container;

defined('TYPO3') || die();

$boot = static function (
    Container $container
): void {
    if (empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'] ?? null)) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'] = [
            'backend' => FastlyBackend::class,
            'groups' => [
                'fastly',
                'news',
                'pages',
            ],
        ];
    }

    if (TYPO3_MODE === 'BE') {
        $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
        $iconRegistry->registerIcon(
            'extension-cdn_fastly-clearcache',
            BitmapIconProvider::class,
            ['source' => 'EXT:cdn_fastly/Resources/Public/Icons/Cache/FastlyClearCache.png']
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = FastlyClearCache::class;
    }
};

$boot(
    GeneralUtility::makeInstance(Container::class)
);
unset($boot);
