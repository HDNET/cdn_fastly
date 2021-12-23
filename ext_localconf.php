<?php

defined('TYPO3_MODE') || die();


$boot = function (\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {

    $container = $objectManager->get(\TYPO3\CMS\Extbase\Object\Container\Container::class);
    $container->registerImplementation(\Fastly\FastlyInterface::class, \Fastly\Fastly::class);

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = \HDNET\CdnFastly\Hooks\FastlyClearCache::class;

    // @todo needed?!?!
    $GLOBALS['TYPO3_CONF_VARS']['BE']['AJAX']['CdnFastly::clearCache'] = [
        'callbackMethod' => \HDNET\CdnFastly\Hooks\FastlyClearCache::class . '->clear',
        'csrfTokenCheck' => true
    ];

    $registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $registry->registerIcon('extension-cdn_fastly-clearcache', \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class, [
        'source' => 'EXT:cdn_fastly/Resources/Public/Icons/Cache/FastlyClearCache.png',
    ]);

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
};

$boot(new \TYPO3\CMS\Extbase\Object\ObjectManager());
unset($boot);
