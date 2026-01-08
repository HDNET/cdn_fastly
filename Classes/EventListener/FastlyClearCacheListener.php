<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\EventListener;

use TYPO3\CMS\Backend\Backend\Event\ModifyClearCacheActionsEvent;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;

final class FastlyClearCacheListener
{
    public function __construct(
        private readonly UriBuilder $uriBuilder,
    ) {}

    public function __invoke(ModifyClearCacheActionsEvent $event): void
    {
        $isAdmin = $this->getBackendUser()->isAdmin();
        $userTsConfig = $this->getBackendUser()->getTSConfig();
        if (!($isAdmin || (($userTsConfig['options.']['clearCache.'] ?? false) && ($userTsConfig['options.']['clearCache.']['fastly'] ?? false)))) {
            return;
        }

        $route = $this->getAjaxUri('ajax_fastly');
        if ($route === null) {
            return;
        }

        $event->addCacheAction([
            'id' => 'cdn_fastly',
            'title' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:cache.title',
            'description' => 'LLL:EXT:cdn_fastly/Resources/Private/Language/locallang.xlf:cache.description',
            'href' => $route,
            'iconIdentifier' => 'extension-cdn_fastly-clearcache',
        ]);
        $event->addCacheActionIdentifier('fastly');
    }

    private function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    private function getAjaxUri(string $routeIdentifier): ?string
    {
        try {
            return (string)$this->uriBuilder->buildUriFromRoute($routeIdentifier);
        } catch (RouteNotFoundException) {
            return null;
        }
    }
}
