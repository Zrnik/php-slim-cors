<?php

namespace Zrnik\SlimCors;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

/**
 * @see https://stackoverflow.com/a/9866124
 */
class CorsMiddleware implements MiddlewareInterface
{
    /**
     * @param string[] $allowedOrigins
     * @param string[] $allowedMethods
     */
    public function __construct(
        private readonly array $allowedOrigins = [],
        private readonly array $allowedMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    )
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $httpOrigin = $request->getServerParams()['HTTP_ORIGIN'] ?? null;

        $response = match ($request->getMethod()) {
            'OPTIONS' => (new ResponseFactory())->createResponse(),
            default => $handler->handle($request),
        };

        if ($httpOrigin !== null && in_array($httpOrigin, $this->allowedOrigins, true)) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $httpOrigin);
            $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
            $response = $response->withHeader('Access-Control-Max-Age', '600');
        } else if ($httpOrigin !== null && count($this->allowedOrigins) === 0) {
            $response = $response->withHeader('Access-Control-Allow-Origin', $httpOrigin);
            $response = $response->withHeader('Access-Control-Allow-Credentials', 'true');
            $response = $response->withHeader('Access-Control-Max-Age', '600');
        }

        if ($request->getMethod() === 'OPTIONS') {
            $accessControlRequestMethods = $request->getHeader("Access-Control-Request-Method");
            if (count($accessControlRequestMethods) > 0) {
                $response = $response->withAddedHeader(
                    'Access-Control-Allow-Methods',
                    implode(', ', $this->allowedMethods)
                );
            }

            $accessControlRequestHeaders = $request->getHeader("Access-Control-Request-Headers");
            $accessControlRequestHeaders = explode(',', implode(',', $accessControlRequestHeaders));

            if (count($accessControlRequestHeaders) > 0) {
                $response = $response->withAddedHeader(
                    'Access-Control-Allow-Headers',
                    implode(', ', $accessControlRequestHeaders)
                );
            }
        }

        return $response;
    }
}
