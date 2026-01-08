<?php

namespace HDNET\CdnFastly\Tests\Unit\EventListener;

use HDNET\CdnFastly\EventListener\FastlyClearCacheListener;
use HDNET\CdnFastly\Tests\Unit\AbstractTestCase;
use TYPO3\CMS\Backend\Routing\UriBuilder;

class FastlyClearCacheListenerTest extends AbstractTestCase
{
    public function testIsLoadable()
    {
        $uriBuilder = $this->getMockBuilder(UriBuilder::class)->disableOriginalConstructor()->getMock();
        $object = new FastlyClearCacheListener($uriBuilder);
        self::assertInstanceOf(FastlyClearCacheListener::class, $object, 'Object should be creatable');
    }
}
