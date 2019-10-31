<?php
declare(strict_types=1);

namespace HDNET\CdnFastly\Tests\Unit\Service;

use HDNET\CdnFastly\Middleware\FastlyMiddleware;
use HDNET\CdnFastly\Tests\Unit\AbstractTest;
use HDNET\CdnFastly\Service\FastlyService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;


class FastlyServiceTest extends AbstractTest
{

    public function testIsLoadable(){
        $object = new FastlyService();
        $this->assertTrue(is_object($object), 'Object should be creatable');
    }

    public function test_purgeAll()
    {
        $this->markTestSkipped('Check create process of the service');
        // 0 TYPO3 Funktionen (__construct)
        $service = new FastlyService();
/*
        // Xclass + Singleton + Logger
        GeneralUtility::makeInstance(FastlyService::class);

        // DI + inizilizeObject() (und natürlich alles von GenralUtility::makeInstance)
        $objectManager = new ObjectManager();
        $objectManager->get();*/

        $response = $service->purgeAll();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
