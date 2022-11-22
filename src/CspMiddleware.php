<?php
/**
 * Content Security Policy Middleware.
 *
 * Add Content-Security-Policy headers for PSR-7 requests.
 * Uses the csp-builder library paragonie/csp-builder.
 *
 * @see        https://github.com/pavlakis/csp-middleware
 *
 * @copyright   Copyright © 2017 Antonios Pavlakis
 * @author      Antonios Pavlakis
 * @license     https://github.com/pavlakis/csp-middleware/blob/master/LICENSE (MIT)
 */

namespace Pavlakis\Middleware\Csp;

use ParagonIE\CSPBuilder\CSPBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CspMiddleware
{
    public const CSP_HEADER_ENABLE = 'Content-Security-Policy';
    public const CSP_HEADER_REPORT_ONLY = 'Content-Security-Policy-Report-Only';

    /**
     * @var CSPBuilder
     */
    private $cspBuilder;

    /**
     * @var bool
     */
    private $reportOnly;

    /**
     * CspMiddleware constructor.
     *
     * @param CSPBuilder $cspBuilder
     * @param bool       $reportOnly
     */
    public function __construct(CSPBuilder $cspBuilder, $reportOnly = true)
    {
        $this->cspBuilder = $cspBuilder;
        $this->reportOnly = $reportOnly;
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request  PSR7 request object
     * @param ResponseInterface      $response PSR7 response object
     * @param callable               $next     Next middleware callable
     *
     * @return ResponseInterface PSR7 response object
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $header = static::CSP_HEADER_ENABLE;
        if ($this->reportOnly) {
            $header = static::CSP_HEADER_REPORT_ONLY;
        }

        $policies = $this->cspBuilder->compile();

        $cspResponse = $response->withAddedHeader($header, $policies);

        return $next($request, $cspResponse);
    }
}
