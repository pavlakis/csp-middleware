[![Build Status](https://travis-ci.org/pavlakis/csp-middleware.svg)](https://travis-ci.org/pavlakis/csp-middleware)
[![Total Downloads](https://img.shields.io/packagist/dt/pavlakis/csp-middleware.svg)](https://packagist.org/packages/pavlakis/csp-middleware)
[![Latest Stable Version](https://img.shields.io/packagist/v/pavlakis/csp-middleware.svg)](https://packagist.org/packages/pavlakis/csp-middleware)
[![codecov](https://codecov.io/gh/pavlakis/csp-middleware/branch/master/graph/badge.svg)](https://codecov.io/gh/pavlakis/csp-middleware)


# CSP Middleware

Add Content-Security-Policy headers using PSR-7 requests. 
Uses the [paragonie/csp-builder](https://github.com/paragonie/csp-builder) package.

## Usage

Adding the middleware is as simple as:

```php

$app->add(new \Pavlakis\Middleware\Csp\CspMiddleware($container->get('csp'));

```

Where `$container->get('csp')` returns an instance of `CSPBuilder` with a CSP configuration.

There is a second parameter `$reportOnly`. It is a boolean and set to `true` by default and it will add the CSP header as `Content-Security-Policy-Report-Only`. This is important so you don't break your application accidentally.

To enable it, pass `false`


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

### Example in Slim3

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

## Resources 

Useful resources for CSP

* [CSP: Let's break stuff](https://www.slideshare.net/Brunty/content-security-policies-lets-break-stuff) - by [Matt Brunt (@brunty)](https://twitter.com/brunty)
* [Report-Uri.io](https://report-uri.io/)
* [Web Fundamentals - CSP](https://developers.google.com/web/fundamentals/security/csp/)