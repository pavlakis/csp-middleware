[![Build Status](https://travis-ci.org/pavlakis/csp-middleware.svg)](https://travis-ci.org/pavlakis/csp-middleware)
[![Total Downloads](https://img.shields.io/packagist/dt/pavlakis/csp-middleware.svg)](https://packagist.org/packages/pavlakis/csp-middleware)
[![Latest Stable Version](https://img.shields.io/packagist/v/pavlakis/csp-middleware.svg)](https://packagist.org/packages/pavlakis/csp-middleware)
[![codecov](https://codecov.io/gh/pavlakis/csp-middleware/branch/master/graph/badge.svg)](https://codecov.io/gh/pavlakis/csp-middleware)


# CSP Middleware

Add Content-Security-Policy headers for PSR-7 requests. Uses the csp-builder library paragonie/csp-builder.

### Usage

Use a `json` file with the csp policies.

Example:

```json
{
  "report-only": false,
  "report-uri": "/csp/enforce",
  "base-uri": [],
  "default-src": [],
  "child-src": {
    "self": false
  },
  "connect-src": {},
  "font-src": {
    "self": true
  },
  "form-action": {
    "self": true
  },
  "frame-ancestors": [],
  "img-src": {
    "self": true
  },
  "media-src": [],
  "object-src": [],
  "plugin-types": [],
  "script-src": {
    "allow": [
      "https://www.google-analytics.com"
    ],
    "self": true,
    "unsafe-inline": false,
    "unsafe-eval": false
  },
  "style-src": {
    "self": true,
    "unsafe-inline": false
  },
  "upgrade-insecure-requests": true
}

```

Example in Slim3

**Dependencies (dependencies.php)**

```php

$container['csp'] = function ($c) {
    $csp = CSPBuilder::fromFile(__DIR__ . '/configs/csp.json');
    return $csp;
};

```

**Application Middleware (middleware.php)**

```php

$app->add(new \Pavlakis\Middleware\Csp\CspMiddleware($container->get('csp'));

```