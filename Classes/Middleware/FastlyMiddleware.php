<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\ApplicationType;
use function array_unique;
use function implode;
use function is_array;
use function mb_strpos;

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
        $response = $response->withHeader('X-CDN', 'enabled');

        return $response;
    }

    protected function isEnvironmentInFrontendMode(): bool
    {
        return ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend();
    }

    protected function isFastlyDisabledOrNotConfigured(): bool
    {
        return !($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord()['fastly'] ?? false);
    }

    protected function appendSurrogateKeys(ResponseInterface $response): ResponseInterface
    {
        if (is_array($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->getCacheTags()) && $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->getCacheTags() !== []) {
            $cacheTags = implode(' ', array_unique($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.cache.collector')->getCacheTags()));
            $response = $response->withHeader('Surrogate-Key', $cacheTags);
        }
        return $response;
    }

    protected function appendSurrogateControl(ResponseInterface $response): ResponseInterface
    {
        $staleTimeout = 14400; // 4 hours
        $staleIfErrorTimeout = 604800; // 168 hours
        $additions = [
            'stale-while-revalidate' => $staleTimeout,
            'stale-if-error' => $staleIfErrorTimeout,
        ];

        $cacheControlHeaderValue = $response->getHeader('Cache-Control')[0] ?? '';
        if (mb_strpos($cacheControlHeaderValue, 'private') !== false) {
            return $response;
        }

        $cacheTimeout = 3600; // Standard: 1 Stunde
        if (preg_match('/max-age=(\d+)/', $cacheControlHeaderValue, $matches)) {
            $cacheTimeout = (int)$matches[1];
        }
        $cacheControlHeaderValue = 'max-age=' . $cacheTimeout . ', public';
        foreach ($additions as $key => $value) {
            $cacheControlHeaderValue .= ',' . $key . '=' . $value;
        }

        return $response->withHeader('Surrogate-Control', $cacheControlHeaderValue);
    }
}
