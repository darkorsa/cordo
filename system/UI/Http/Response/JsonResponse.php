<?php

namespace System\UI\Http\Response;

use GuzzleHttp\Psr7\Response;
use System\UI\ResponseInterface;

class JsonResponse implements ResponseInterface
{
    private $response;
    
    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function send()
    {
        http_response_code($this->response->getStatusCode());

        header("Content-Type:{application/json}");
        header("charset:{utf-8}");

        // additional header
        foreach ($this->response->getHeaders() as $key => $val) {
            header("{$key}:{".current($val)."}");
        }

        echo $this->response->getBody();
        exit;
    }
}
