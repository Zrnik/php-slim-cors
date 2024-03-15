# Slim Framework 4 CORS Middleware

I just wanted to remove CORS error from my `React` 
application to my Slim Framework 4 API.

## Installation

```
composer require zrnik/php-slim-cors
```

Requires Slim Framework 4 & PHP 8.1+

## Usage

Add this middleware to your `\Slim\App`:

```php
$app = new \Slim\App();
$app->add(new \Zrnik\SlimCors\CorsMiddleware());
```

That's it. There are optional parameters for the `CorsMiddleware` object:

```php
$app = new \Slim\App();
$app->add(
    new \Zrnik\SlimCors\CorsMiddleware(
        allowedOrigins: [
            'my-frontend.app',
            'another-app.com',
        ],
        allowedMethods: [
            'GET', 'POST'
        ]               
    )
);
```