<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Tests\Unit\Controller;

use HDNET\CdnFastly\Controller\ClearCacheController;
use HDNET\CdnFastly\Tests\Unit\AbstractTestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use TYPO3\CMS\Core\Cache\CacheManager;

class ClearCacheControllerTest extends AbstractTestCase
{
    /** @test */
    public function canBeInstantiated()
    {
        $responseFactory = $this->getMockBuilder(ResponseFactoryInterface::class)->getMock();
        $cacheManager = $this->getMockBuilder(CacheManager::class)->getMock();
        $object = new ClearCacheController('dummy', $responseFactory, $cacheManager);
        self::assertInstanceOf(ClearCacheController::class, $object);
    }
}
