<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Cache;

use Exception;
use HDNET\CdnFastly\Service\FastlyService;
use Override;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Cache\CacheTag;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use function array_map;

class FastlyBackend extends NullBackend implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var FastlyService|null
     */
    protected ?FastlyService $fastlyService = null;

    protected function getFastlyService(): ?FastlyService
    {
        if ($this->fastlyService === null) {
            try {
                $this->fastlyService = GeneralUtility::makeInstance(FastlyService::class);
            } catch (Exception $e) {
                if ($this->logger) {
                    $this->logger->error('Fastly service was not built: ' . $e->getMessage());
                }
            }
        }
        return $this->fastlyService;
    }

    public function flush(): void
    {
        $fastlyService = $this->getFastlyService();
        if ($fastlyService === null) {
            return;
        }
        $fastlyService->purgeAll();
    }

    /**
     * @param string $tag
     */
    public function flushByTag($tag): void
    {
        $fastlyService = $this->getFastlyService();
        if ($fastlyService === null) {
            return;
        }
        $fastlyService->purgeKey((string)$tag);
    }

    #[Override]
    public function flushByTags(array $tags): void
    {
        $fastlyService = $this->getFastlyService();
        if ($fastlyService === null) {
            return;
        }

        $tagStrings = [];
        foreach ($tags as $tag) {
            if ($tag instanceof CacheTag) {
                $tagStrings[] = $tag->name;
            } else {
                $tagStrings[] = (string)$tag;
            }
        }

        $fastlyService->purgeKeys($tagStrings);
    }
}
