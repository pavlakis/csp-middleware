<?php
/**
 * @link        https://github.com/pavlakis/csp-middleware
 * @copyright   Copyright © 2017 Antonios Pavlakis
 * @author      Antonios Pavlakis
 * @license     https://github.com/pavlakis/csp-middleware/blob/master/LICENSE (MIT)
 */
namespace Pavlakis\Tests\Middleware\Csp;

use ParagonIE\CSPBuilder\CSPBuilder;
use Pavlakis\Middleware\Csp\CspMiddleware;
use Pavlakis\Tests\Middleware\Csp\Factory\RequestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CspMiddlewareTest extends TestCase
{
    /**
     * @var
     */
    private $mockResponse;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    public function setUp()
    {
        $this->request = RequestFactory::createRequest();
        $this->mockResponse = $this->getMockBuilder(ResponseInterface::class)->setMethods(['withAddedHeader']);
    }

    public function testReportOnlyHeaderWithCspPolicies()
    {
        $cspBuilder = CSPBuilder::fromFile(__DIR__ . '/fixtures/csp.json');
        $cspMiddleware = new CspMiddleware($cspBuilder, true);

        $request = $this->request;
        $response = $this->mockResponse->getMockForAbstractClass();
        $response->expects(static::once())
            ->method('withAddedHeader')
            ->with( static::equalTo( 'Content-Security-Policy-Report-Only') , static::equalTo("base-uri 'none'; default-src 'none'; child-src; connect-src 'none'; font-src 'self'; form-action 'self'; frame-ancestors 'none'; img-src 'self'; media-src 'none'; object-src 'none'; script-src 'self' https://www.google-analytics.com; style-src 'self'; report-uri /csp/enforce; upgrade-insecure-requests"));


        $next = function ($request, $response) {
            return $response;
        };

        $cspMiddleware($request, $response, $next);
    }

    public function testReportHeader()
    {
        $cspBuilder = CSPBuilder::fromFile(__DIR__ . '/fixtures/csp.json');
        $cspMiddleware = new CspMiddleware($cspBuilder, false);

        $request = $this->request;
        $response = $this->mockResponse->getMockForAbstractClass();
        $response->expects(static::once())
            ->method('withAddedHeader')
            ->with('Content-Security-Policy');


        $next = function ($request, $response) {
            return $response;
        };

        $cspMiddleware($request, $response, $next);
    }
}
