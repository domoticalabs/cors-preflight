# cors-preflight
Lumen/Laravel package that creates a middleware for adding CORS headers to all incoming requests.
It also adds *Access-Control-Allow-Methods* header to the OPTIONS requests with all the routes-registered methods. This is useful for [preflight](https://developer.mozilla.org/en-US/docs/Glossary/Preflight_request) requests.

## Example:
With this routes:
```php
$router->get('/test', function () use ($router) {
    return $router->app->version();
});
$router->post('/test', function () use ($router) {
    return $router->app->version();
});
```
The response headers for OPTIONS request on `/test` route is:
```
Access-Control-Allow-Origin →*
Access-Control-Allow-Credentials →true
Access-Control-Max-Age →86400
Access-Control-Allow-Headers →Content-Type, Authorization, X-Requested-With
Access-Control-Allow-Methods →OPTIONS,GET,POST
```

## Installation
Install this package using composer:
```composer require dusterio/lumen-passport```

### Enable middleware
You need to edit your `bootstrap\app.php` file and uncomment this line:
```php
$app->withFacades();
```
and add this lines:
```php
$app->middleware([
   Domoticalabs\CorsPreflight\CorsPreflightMiddleware::class
]);
```

## License
The MIT License (MIT)
Copyright (c) 2018 [Domotica Labs](https://www.domoticalabs.com)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
