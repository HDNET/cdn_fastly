<?php

/**
 * Clear Cache hook for the Backend.
 */
declare(strict_types=1);

namespace HDNET\CdnFastly\Hooks;

use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Toolbar\ClearCacheActionsHookInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Exception\NoSuchCacheGroupException;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Clear Cache hook for the Backend.
 */
class FastlyClearCache implements ClearCacheActionsHookInterface
{
    /**
     * Modifies CacheMenuItems array.
     *
     * @param array<mixed> $cacheActions Array of CacheMenuItems
     * @param array<mixed> $optionValues Array of AccessConfigurations-identifiers (typically used by userTS with options.clearCache.identifier)
     */
    public function manipulateCacheActions(&$cacheActions, &$optionValues): void
    {
        $isAdmin = $GLOBALS['BE_USER']->isAdmin();
        $userTsConfig = $GLOBALS['BE_USER']->getTSConfig();
        if (!($isAdmin || ( ($userTsConfig['options.']['clearCache.'] ?? false) &&  ($userTsConfig['options.']['clearCache.']['fastly'] ?? false)))) {
            return;
        }

        $route = $this->getAjaxUri();
        if (!$route) {
            return;
        }

        $cacheActions[] = [
            'id' => 'cdn_fastly',
            'title' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:cache.title',
            'description' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:cache.description',
            'href' => $route,
            'iconIdentifier' => 'extension-cdn_fastly-clearcache',
        ];

        $optionValues[] = 'fastly';
    }

    /**
     * @throws NoSuchCacheGroupException
     *
     * @return HtmlResponse|void
     */
    public function clear()
    {
        $cacheManager = GeneralUtility::makeInstance(CacheManager::class);
        $cacheManager->flushCachesInGroup('fastly');

        return new HtmlResponse('');
    }

    /**
     * Get Ajax URI.
     */
    protected function getAjaxUri(): string
    {
        /** @var UriBuilder $uriBuilder */
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        try {
            $routeIdentifier = 'ajax_fastly';
            $uri = $uriBuilder->buildUriFromRoute($routeIdentifier);
        } catch (RouteNotFoundException $e) {
            return '';
        }

        return (string)$uri;
    }
}
