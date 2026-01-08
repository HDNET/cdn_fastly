<?php

namespace HDNET\CdnFastly\Tests\Unit\Cache;

use HDNET\CdnFastly\Cache\FastlyBackend;
use HDNET\CdnFastly\Tests\Unit\AbstractTestCase;

class FastlyBackendTest extends AbstractTestCase
{
    public function testIsLoadable()
    {
        $object = new FastlyBackend(null);
        self::assertInstanceOf(FastlyBackend::class, $object, 'Object should be creatable');
    }
}
