<?php

namespace HDNET\CdnFastly\Tests\Unit\Cache;

use HDNET\CdnFastly\Cache\FastlyBackend;
use HDNET\CdnFastly\Tests\Unit\AbstractTest;

class FastlyBackendTest extends AbstractTest
{
    public function testIsLoadable()
    {
        $object = new FastlyBackend(null);
        self::assertTrue(is_object($object), 'Object should be creatable');
    }
}
