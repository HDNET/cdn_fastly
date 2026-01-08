<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Cache;

use Exception;
use HDNET\CdnFastly\Service\FastlyService;
use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class FastlyBackend extends NullBackend
{
    private ?FastlyService $fastlyService = null;

    private bool $initialized = false;

    public function flush(): void
    {
        $this->initialize(fn() => $this->fastlyService->purgeAll());
    }

    public function flushByTag($tag): void
    {
        $this->initialize(fn() => $this->fastlyService->purgeKey((string)$tag));
    }

    public function flushByTags(array $tags): void
    {
        $this->initialize(fn() => $this->fastlyService->purgeKeys($tags));
    }

    private function initialize(callable $callback): void
    {
        if (!$this->initialized) {
            try {
                $this->fastlyService = GeneralUtility::makeInstance(FastlyService::class);
            } catch (Exception) {
                $this->logger?->error('Fasty service was not build');
            }
            $this->initialized = true;
        }

        if ($this->fastlyService === null) {
            $this->logger?->error('Fasty service was not build');
            return;
        }
        $callback();
    }
}
