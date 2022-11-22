<?php
/**
 * @see        https://github.com/pavlakis/csp-middleware
 *
 * @copyright   Copyright © 2017 Antonios Pavlakis
 * @author      Antonios Pavlakis
 * @license     https://github.com/pavlakis/csp-middleware/blob/master/LICENSE (MIT)
 */

namespace Pavlakis\Tests\Middleware\Csp;

use PHPUnit\Framework\TestCase;
use ParagonIE\CSPBuilder\CSPBuilder;
use Psr\Http\Message\ResponseInterface;
use Pavlakis\Middleware\Csp\CspMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Pavlakis\Tests\Middleware\Csp\Factory\RequestFactory;

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

    protected function setUp(): void
    {
        $this->request = RequestFactory::createRequest();
        $this->mockResponse = $this->getMockForAbstractClass(ResponseInterface::class);
    }

    public function test_report_only_header_with_csp_policies(): void
    {
        $cspBuilder = CSPBuilder::fromFile(__DIR__.'/fixtures/csp.json');
        $cspMiddleware = new CspMiddleware($cspBuilder, true);

        $request = $this->request;
        $response = $this->mockResponse;
        $response->expects(static::once())
            ->method('withAddedHeader')
            ->with(static::equalTo('Content-Security-Policy-Report-Only'), static::equalTo("base-uri 'none'; default-src 'none'; child-src; connect-src 'none'; font-src 'self'; form-action 'self'; frame-ancestors 'none'; img-src 'self'; media-src 'none'; object-src 'none'; script-src 'self' https://www.google-analytics.com; style-src 'self'; report-uri /csp/enforce; upgrade-insecure-requests"))
            ->willReturn($response);

        $next = function ($request, $response) {
            return $response;
        };

        $cspMiddleware($request, $response, $next);
    }

    public function test_report_header(): void
    {
        $cspBuilder = CSPBuilder::fromFile(__DIR__.'/fixtures/csp.json');
        $cspMiddleware = new CspMiddleware($cspBuilder, false);

        $request = $this->request;
        $response = $this->mockResponse;
        $response->expects(static::once())
            ->method('withAddedHeader')
            ->with('Content-Security-Policy')
            ->willReturn($response);

        $next = function ($request, $response) {
            return $response;
        };

        $cspMiddleware($request, $response, $next);
    }
}
