<?php

use HDNET\CdnFastly\Cache\FastlyBackend;
use HDNET\CdnFastly\Hooks\FastlyClearCache;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

defined('TYPO3') || die();

if (empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'] ?? null)) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['CdnFastly'] = [
        'backend' => FastlyBackend::class,
        'groups' => [
            'fastly'
        ],
    ];
}

if (($GLOBALS['TYPO3_REQUEST'] ?? null) && ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isBackend()) {
    $iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);
    $iconRegistry->registerIcon(
        'extension-cdn_fastly-clearcache',
        BitmapIconProvider::class,
        ['source' => 'EXT:cdn_fastly/Resources/Public/Icons/Cache/FastlyClearCache.png']
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = FastlyClearCache::class;
}
