<?php
declare(strict_types=1);

namespace Pavel\CdnFastly\Tests\Unit\Service;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pavel\CdnFastly\Service\FastlyService;
use Psr\Http\Message\ResponseInterface;


class FastlyServiceTest extends UnitTestCase
{
    public function test_purgeAll()
    {
        $service = new FastlyService();

        $response = $service->purgeAll();

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
