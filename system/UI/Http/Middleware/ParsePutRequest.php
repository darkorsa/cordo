<?php

namespace System\UI\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ParsePutRequest implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $contentType = $request->getHeader('content-type');
        if ($request->getMethod() === 'PUT' && in_array('application/x-www-form-urlencoded', $contentType, true)) {
                $body = [];
                mb_parse_str((string)$request->getBody(), $body);
                $request = $request->withParsedBody($body);
        }

        return $handler->handle($request);
    }
}
