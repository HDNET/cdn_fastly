<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Controller;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Cache\CacheManager;

final class ClearCacheController
{
    public function __construct(
        public readonly string $cacheGroupIdentifier,
        private readonly ResponseFactoryInterface $responseFactory,
        private readonly CacheManager $cacheManager,
    ) {}

    public function __invoke(): ResponseInterface
    {
        $this->cacheManager->flushCachesInGroup($this->cacheGroupIdentifier);

        $response = $this->responseFactory
            ->createResponse()
            ->withHeader('Content-Type', 'application/json; charset=utf-8');
        $response->getBody()->write(\json_encode('ok'));

        return $response;
    }
}
