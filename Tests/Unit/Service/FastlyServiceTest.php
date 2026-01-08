<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Tests\Unit\Service;

use HDNET\CdnFastly\Service\ConfigurationServiceInterface;
use HDNET\CdnFastly\Service\FastlyService;
use HDNET\CdnFastly\Tests\Unit\AbstractTestCase;

class FastlyServiceTest extends AbstractTestCase
{
    public function testIsLoadable()
    {
        $configurationService = $this->getMockBuilder(ConfigurationServiceInterface::class)->getMock();
        $object = new FastlyService($configurationService);
        self::assertInstanceOf(FastlyService::class, $object, 'Object should be creatable');
    }
}
