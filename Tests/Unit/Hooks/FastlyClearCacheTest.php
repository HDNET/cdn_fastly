<?php
/**
 *
 */

namespace HDNET\CdnFastly\Tests\Unit\Hooks;


use HDNET\CdnFastly\Hooks\FastlyClearCache;
use HDNET\CdnFastly\Tests\Unit\AbstractTest;

class FastlyClearCacheTest extends AbstractTest
{

    public function testIsLoadable(){
        $object = new FastlyClearCache();
        $this->assertTrue(is_object($object), 'Object should be creatable');
    }

}
