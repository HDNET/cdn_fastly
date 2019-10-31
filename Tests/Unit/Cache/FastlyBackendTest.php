<?php
/**
 *
 */

namespace HDNET\CdnFastly\Tests\Unit\Cache;


use HDNET\CdnFastly\Cache\FastlyBackend;
use HDNET\CdnFastly\Hooks\FastlyClearCache;
use HDNET\CdnFastly\Tests\Unit\AbstractTest;

class FastlyBackendTest extends AbstractTest
{

    public function testIsLoadable(){
        $object = new FastlyBackend(null);
        $this->assertTrue(is_object($object), 'Object should be creatable');
    }
}
