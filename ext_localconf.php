<?php

defined('TYPO3_MODE') || die();

$boot = static function (
    \TYPO3\CMS\Extbase\Object\Container\Container $container
): void {
    $container->registerImplementation(
        \Fastly\FastlyInterface::class,
        \Fastly\Fastly::class
    );

    if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'] = [
            'backend' => HDNET\CdnFastly\Cache\FastlyBackend::class,
            'groups' => [
                'fastly',
                'pages',
                'news',
            ],
        ];
    }

    if (TYPO3_MODE === 'BE') {
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
        $iconRegistry->registerIcon(
            'extension-cdn_fastly-clearcache',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:cdn_fastly/Resources/Public/Icons/Cache/FastlyClearCache.png']
        );

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = \HDNET\CdnFastly\Hooks\FastlyClearCache::class;
    }
};

$boot(
    \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
);
unset($boot);
