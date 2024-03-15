<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Middleware\BodyParsingMiddleware;

class CorsMiddleware extends BodyParsingMiddleware
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {



        return $handler->handle($request);
    }


    /* public function __invoke(RequestInterface $request, RequestHandler $handler): ResponseInterface
     {
         //$response = $handler();
         //handle($request);




         return $response;
     }*/
}