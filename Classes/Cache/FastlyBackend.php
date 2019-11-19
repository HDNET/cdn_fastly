<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Cache;

use HDNET\CdnFastly\Service\FastlyService;
use TYPO3\CMS\Core\Cache\Backend\NullBackend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class FastlyBackend extends NullBackend
{
    /**
     * @var FastlyService
     */
    protected $fastlyService;

    public function initializeObject(): void
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->injectFastlyService($objectManager->get(FastlyService::class));
    }

    public function injectFastlyService(FastlyService $fastlyService)
    {
        $this->fastlyService = $fastlyService;
    }

    public function flush(): void
    {
        $this->fastlyService->purgeAll();
    }

    /**
     * @param string $tag
     */
    public function flushByTag($tag)
    {
        $this->fastlyService->purgeKey($tag);
    }

    public function flushByTags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->flushByTag($tag);
        }
    }
}
