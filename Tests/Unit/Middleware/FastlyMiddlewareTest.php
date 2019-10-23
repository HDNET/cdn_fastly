<?php
declare(strict_types=1);

namespace Pavel\CdnFastly\Tests\Unit\Middleware;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use Pavel\CdnFastly\Middleware\FastlyMiddleware;
use Pavel\CdnFastly\Tests\Unit\RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\EnvironmentService;


class FastlyMiddlewareTest extends UnitTestCase
{
    public function test1(): void
    {
        $handler = new RequestHandler();
        $request = $handler->getDummyRequest();

        $middleware = new FastlyMiddleware();
        $response = $middleware->process($request, $handler);

        var_dump($response);
        die();


        // Middleware

    }

    public function test_is_response_type_a_ResponseInterface()
    {
        $middleware = new FastlyMiddleware();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $response = $middleware->process($request, $handler);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function test_get_XCDN_Header_if_Fastly_is_disabled()
    {
        $middleware = new FastlyMiddleware();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();
        $handler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $handler->method('handle')->willReturn(new Response());
        $environmentServiceMock = $this->getMockBuilder(EnvironmentService::class)->setMethods(['isEnvironmentInFrontendMode'])->getMock();
        $environmentServiceMock->method('isEnvironmentInFrontendMode')->willReturn(true);

        $GLOBALS['TSFE'] = new class
        {
            public $page = [
                'fastly' => false
            ];

            public function getPageCacheTags()
            {
                return [];
            }

            public function get_cache_timeout()
            {
                return 1337;
            }
        };

        GeneralUtility::setSingletonInstance(EnvironmentService::class, $environmentServiceMock);

        $response = $middleware->process($request, $handler);

        $this->assertTrue($response->hasHeader('X-CDN'));
        $this->assertEquals('disabled', $response->getHeader('X-CDN')[0]);
    }

    public function test_PageCacheKey()
    {
        $middleware = new FastlyMiddleware();
        $request = $this->getMockBuilder(ServerRequestInterface::class)->getMock();

        $handler = $this->getMockBuilder(RequestHandlerInterface::class)->getMock();
        $handler->method('handle')->willReturn(new Response());

        $environmentServiceMock = $this->getMockBuilder(EnvironmentService::class)->setMethods(['isEnvironmentInFrontendMode'])->getMock();
        $environmentServiceMock->method('isEnvironmentInFrontendMode')->willReturn(true);

        $GLOBALS['TSFE'] = new class
        {
            public $page = [
                'fastly' => true
            ];

            public $testKeys = ['firstTestKey'];

            public function getPageCacheTags()
            {
                return $this->testKeys;
            }

            public function get_cache_timeout()
            {
                return 1337;
            }
        };

        GeneralUtility::setSingletonInstance(EnvironmentService::class, $environmentServiceMock);

        $response = $middleware->process($request, $handler);

        $this->assertTrue($response->hasHeader('X-CDN'),'Expected Header');
        $this->assertTrue($response->hasHeader('Surrogate-Key'),'Expected Header');
        $this->assertEquals('enabled', $response->getHeader('X-CDN')[0],'Expected Value "enabled" in Header "X-CDN"');
        $this->assertEquals('firstTestKey', $response->getHeader('Surrogate-Key')[0],'Expected Value "firstTestKey" in Header "Surrogate-Key"');

        $GLOBALS['TSFE']->testKeys[] = 'secondTestKey';
        $response = $middleware->process($request, $handler);
        $this->assertEquals('firstTestKey secondTestKey', $response->getHeader('Surrogate-Key')[0], 'Expected 2 tags seperated by ');

        $GLOBALS['TSFE']->testKeys = [];
        $response = $middleware->process($request, $handler);
        $this->assertFalse($response->hasHeader('Surrogate-Key'), 'Expected no Header since there are no tags');
    }
}
