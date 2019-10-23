<?php
/**
 *
 */


declare(strict_types=1);

namespace Pavel\CdnFastly\Tests\Unit;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Http\ServerRequestFactory;
use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RequestHandler implements RequestHandlerInterface
{

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write('Ich bin die Webseite');
        return $response;
    }

    public function getDummyRequest()
    {
        $serverParameters = $_SERVER;
        $headers = [];

        $method = $serverParameters['REQUEST_METHOD'] ?? 'GET';
        $uri = new Uri('https://www.pavels-welt.de');

        $request = new ServerRequest(
            $uri,
            $method,
            'php://input',
            $headers,
            $serverParameters,
            null
        );

        if (!empty($_COOKIE)) {
            $request = $request->withCookieParams($_COOKIE);
        }
        $queryParameters = GeneralUtility::_GET();
        if (!empty($queryParameters)) {
            $request = $request->withQueryParams($queryParameters);
        }
        $parsedBody = GeneralUtility::_POST();
        if (empty($parsedBody) && in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
            parse_str(file_get_contents('php://input'), $parsedBody);
        }
        if (!empty($parsedBody)) {
            $request = $request->withParsedBody($parsedBody);
        }
        return $request;
    }
}
