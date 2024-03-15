<?php

namespace Zrnik\SlimCors;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Middleware\BodyParsingMiddleware;

/**
 * @see https://stackoverflow.com/a/9866124
 */
class CorsMiddleware extends BodyParsingMiddleware
{
    public function __construct(
        private readonly array $allowedOrigins = [],
        private readonly array $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    )
    {
        parent::__construct();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        $httpOrigin = $request->getServerParams()['HTTP_ORIGIN'] ?? null;

        if ($httpOrigin !== null && in_array($httpOrigin, $this->allowedOrigins, true)) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $httpOrigin);
            $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
            $response = $response->withHeader('Access-Control-Max-Age', '86400');
        } else if (count($this->allowedOrigins) === 0) {
            $response = $response->withHeader('Access-Control-Allow-Origin', '*');
            $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
            $response = $response->withHeader('Access-Control-Max-Age', '86400');
        }

        $httpAccessControlRequestMethod = $request->getServerParams()['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] ?? null;

        if ($httpAccessControlRequestMethod !== null) {
            $response = $response->withHeader(
                'Access-Control-Allow-Methods',
                implode(', ', $this->allowedMethods)
            );
        }

        $httpAccessControlRequestHeaders = $request->getServerParams()['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'] ?? null;

        if ($httpAccessControlRequestMethod !== null) {
            $response = $response->withHeader(
                'Access-Control-Allow-Headers',
                $httpAccessControlRequestHeaders
            );
        }

        return $response;
    }
}
