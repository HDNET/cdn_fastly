<?php

declare(strict_types=1);

namespace Pavel\CdnFastly\Cache;

use Pavel\CdnFastly\Service\FastlyService;
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
        $this->fastlyService = $objectManager->get(FastlyService::class);
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

    /**
     * @param array $tags
     */
    public function flushByTags(array $tags)
    {
        foreach ($tags as $tag) {
            $this->flushByTag($tag);
        }
    }
}
