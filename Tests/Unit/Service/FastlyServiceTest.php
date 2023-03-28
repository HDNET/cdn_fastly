<?php

declare(strict_types=1);

namespace HDNET\CdnFastly\Tests\Unit\Service;

use HDNET\CdnFastly\Service\FastlyService;
use HDNET\CdnFastly\Tests\Unit\AbstractTest;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Object\Container\Container;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class FastlyServiceTest extends AbstractTest
{
    public function testIsLoadable()
    {
        $object = new FastlyService();
        self::assertTrue(is_object($object), 'Object should be creatable');
    }

    public function test_purgeAll()
    {
        //if(...) {
        //    $this->markTestSkipped(....);
        //} ggf. umgebngsvariablen

        $objectManager = new ObjectManager();
        $container = $objectManager->get(Container::class);

        $service = $objectManager->get(FastlyService::class);

        $response = $service->purgeAll();

        self::assertInstanceOf(ResponseInterface::class, $response);
    }
}
