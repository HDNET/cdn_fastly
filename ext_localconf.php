<?php

defined('TYPO3_MODE') || die();


$boot = function () {


    $objectManager = new \TYPO3\CMS\Extbase\Object\ObjectManager();
    $container = $objectManager->get(\TYPO3\CMS\Extbase\Object\Container\Container::class);
    $container->registerImplementation(\Fastly\FastlyInterface::class, \Fastly\Fastly::class);

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = \HDNET\CdnFastly\Hooks\FastlyClearCache::class;

    $registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $registry->registerIcon('extension-cdn_fastly-clearcache', \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class, [
        'source' => 'EXT:cdn_fastly/Resources/Public/Icons/Cache/FastlyClearCache.png',
    ]);

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'] = [
        'backend' => HDNET\CdnFastly\Cache\FastlyBackend::class,
        'groups' => [
            'fastly',
            'pages',
            'news',
        ],
    ];
};

$boot();
unset($boot);
