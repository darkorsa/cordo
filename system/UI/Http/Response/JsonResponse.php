<?php

declare(strict_types=1);

namespace System\UI\Http\Response;

use Psr\Http\Message\ResponseInterface;

class JsonResponse implements \System\UI\ResponseInterface
{
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function send(): void
    {
        http_response_code($this->response->getStatusCode());

        header("Content-Type:application/json");
        header("charset:utf-8");

        // additional headers
        foreach ($this->response->getHeaders() as $key => $val) {
            header("{$key}:{" . current($val) . "}");
        }

        $body = (string) $this->response->getBody();

        echo $this->isJson($body) ? $body : json_encode($body);

        exit;
    }

    private function isJson(string $string): bool
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }
}
