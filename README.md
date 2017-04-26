#CSP Middleware

Add Content-Security-Policy headers for PSR-7 requests. Uses the csp-builder library paragonie/csp-builder.

### Usage

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