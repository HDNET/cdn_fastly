<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Middleware;

use TYPO3\CMS\Core\Http\ApplicationType;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;

class FastlyMiddleware implements MiddlewareInterface
{
    /**
     * Process an incoming server request.
     *
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     *
     * States:
     * Content          | FE User | Not FE User
     * Page             | HIT     | HIT
     * Page (No Fastly) | PASS    | PASS
     * News             | HIT     | HIT
     * PaidNews         | PASS    | HIT (Paywall Version)
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (!$this->isEnvironmentInFrontendMode()) {
            return $response;
        }

        if ($this->isFastlyDisabledOrNotConfigured()) {
            return $response
                ->withHeader('Cache-Control', 'private')
                ->withHeader('X-CDN', 'disabled');
        }

        $response = $this->appendSurrogateKeys($response);
        $response = $this->appendSurrogateControl($response);
        $response = $this->updateCacheControl($response);
        $response = $response->withHeader('X-CDN', 'enabled');

        return $response;
    }

    protected function isEnvironmentInFrontendMode(): bool
    {
        // We don't need extbase here, so no ObjectManager, yet.
        $environmentService = GeneralUtility::makeInstance(EnvironmentService::class);

        return ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend();
    }

    protected function isFastlyDisabledOrNotConfigured(): bool
    {
        return !($GLOBALS['TSFE']->page['fastly'] ?? false);
    }

    protected function appendSurrogateKeys(ResponseInterface $response): ResponseInterface
    {
        if (\is_array($GLOBALS['TSFE']->getPageCacheTags()) && \count($GLOBALS['TSFE']->getPageCacheTags()) > 0) {
            $cacheTags = \implode(' ', \array_unique($GLOBALS['TSFE']->getPageCacheTags()));
            $response = $response->withHeader('Surrogate-Key', $cacheTags);
        }

        return $response;
    }

    protected function appendSurrogateControl(ResponseInterface $response): ResponseInterface
    {
        return $response->withHeader('Surrogate-Control', 'max-age=' . $GLOBALS['TSFE']->get_cache_timeout());
    }

    protected function updateCacheControl(ResponseInterface $response): ResponseInterface
    {
        if (!$response->hasHeader('Cache-Control')) {
            return $response;
        }

        $staleTimeout = 14400; // 4 hours
        $additions = [
            'stale-while-revalidate' => $staleTimeout,
            'stale-if-error' => $staleTimeout,
        ];

        $cacheControlHeaderValue = $response->getHeader('Cache-Control')[0];
        if (false !== \mb_strpos($cacheControlHeaderValue, 'private')) {
            return $response;
        }

        $cacheControlHeaderValue = 'max-age=10';
        foreach ($additions as $key => $value) {
            $cacheControlHeaderValue .= ',' . $key . '=' . $value;
        }

        return $response->withHeader('Cache-Control', $cacheControlHeaderValue);
    }
}
