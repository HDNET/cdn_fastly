<?php
declare(strict_types=1);

namespace HDNET\CdnFastly\Tests\Unit\Service;

use Fastly\FastlyFake;
use Fastly\FastlyInterface;
use HDNET\CdnFastly\Middleware\FastlyMiddleware;
use HDNET\CdnFastly\Service\ConfigurationService;
use HDNET\CdnFastly\Service\ConfigurationServiceInterface;
use HDNET\CdnFastly\Tests\Unit\AbstractTest;
use HDNET\CdnFastly\Service\FastlyService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Container\Container;
use TYPO3\CMS\Extbase\Object\ObjectManager;


class FastlyServiceTest extends AbstractTest
{

    public function testIsLoadable(){
        $object = new FastlyService();
        $this->assertTrue(is_object($object), 'Object should be creatable');
    }

    public function test_purgeAll()
    {
        #if(...) {
        #    $this->markTestSkipped(....);
        #} ggf. umgebngsvariablen

        #$this->markTestSkipped('Check create process of the service');

        // 0 TYPO3 Funktionen (__construct)
        #$service = new FastlyService();

        // Xclass + Singleton + Logger
        #$service = GeneralUtility::makeInstance(FastlyService::class);

        // DI + initializeObject() (und natürlich alles von GeneralUtility::makeInstance)




        $objectManager = new ObjectManager();
        $container = $objectManager->get(Container::class);

        $container->registerImplementation(ConfigurationServiceInterface::class, ConfigurationTestService::class);
        $container->registerImplementation(FastlyInterface::class, FastlyFake::class);

        $service = $objectManager->get(FastlyService::class);

        $response = $service->purgeAll();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}

class ConfigurationTestService extends ConfigurationService
{
    protected function findConfiguration(): array
    {
        // .... ggf. umgebungsvariablen
        return [
            'apiKey' => 'asdasd',
            'serviceId' => 'valid',
        ];
    }
};
