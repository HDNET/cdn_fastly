<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\ApplicationType;

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

        if (!$this->isEnvironmentInFrontendMode($request)) {
            return $response;
        }

        if ($this->isFastlyDisabledOrNotConfigured()) {
            return $response
                ->withHeader('Cache-Control', 'private')
                ->withHeader('X-CDN', 'disabled');
        }

        $response = $this->appendSurrogateKeys($response);
        $response = $this->appendSurrogateControl($response);
        return $response->withHeader('X-CDN', 'enabled');
    }

    protected function isEnvironmentInFrontendMode(ServerRequestInterface $request): bool
    {
        return ApplicationType::fromRequest($request)->isFrontend();
    }

    protected function isFastlyDisabledOrNotConfigured(): bool
    {
        return !($GLOBALS['TSFE']->page['fastly'] ?? false);
    }

    protected function appendSurrogateKeys(ResponseInterface $response): ResponseInterface
    {
        if (\is_array($GLOBALS['TSFE']->getPageCacheTags()) && $GLOBALS['TSFE']->getPageCacheTags() !== []) {
            $cacheTags = \implode(' ', \array_unique($GLOBALS['TSFE']->getPageCacheTags()));
            $response = $response->withHeader('Surrogate-Key', $cacheTags);
        }
        return $response;
    }

    protected function appendSurrogateControl(ResponseInterface $response): ResponseInterface
    {
        $cacheControlHeaderValue = $response->getHeader('Cache-Control')[0] ?? '';
        if (\mb_strpos($cacheControlHeaderValue, 'private') !== false) {
            return $response;
        }

        $staleTimeout = 14400; // 4 hours
        $staleIfErrorTimeout = 604800; // 168 hours
        $additions = [
            'stale-while-revalidate' => $staleTimeout,
            'stale-if-error' => $staleIfErrorTimeout,
        ];

        $cacheControlHeaderValue = 'max-age=' . $GLOBALS['TSFE']->get_cache_timeout() . ', public';
        foreach ($additions as $key => $value) {
            $cacheControlHeaderValue .= ',' . $key . '=' . $value;
        }

        return $response->withHeader('Surrogate-Control', $cacheControlHeaderValue);
    }
}
