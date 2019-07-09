# Slim Authorization Middleware

This package provides a basic middleware to implement authorization of any type.


## Installation

```
composer require crtl/slim-auth-middleware
```

## Usage

The package already comes with a prebuild implementation for HTTP-Basic Authroziation.

```php
<?php
use Crtl\AuthorizationMiddleware\BasicAuthorization;

$app = new \Slim\App();

$app->add(
    new BasicAuthorization([
        BasicAuthorization::CONFIG_ENABLE => true,
        BasicAuthorization::CONFIG_USER => "secret",
        BasicAuthorization::CONFIG_SECRET => "password"
    ])
);

$app->get("/", function($request, $response) use ($app) {
    $body = $response->getBody();
    $body->write("Authorized");
    
    return $response->withBody($body);
});


$app->run();
```

If the request is authorized the app will call the next middleware. Otherwise `Crtl\AuthorizationMiddleware\AbstractAuthorization::getErrorResponse` will be called.

To implement a custom authorization just extend `Crtl\AuthorizationMiddleware\AbstractAuthorization` and implement the <br/>
`protected isAuthorized() : bool` method.


```php
<?php

class CustomAuthorization extends \Crtl\AuthorizationMiddleware\AbstractAuthorization{
    
    protected function isAuthorized(): bool {
        
        /* @var \Psr\Http\Message\ResponseInterface $response */
        $response = $this->response;
        
        /* @var \Psr\Http\Message\RequestInterface $request */
        $request = $this->request;
        
        /* check if authorized */
        
        return true;
        
    }
    
}

```
