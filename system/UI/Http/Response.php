<?php

namespace System\UI\Http;

class Response
{
    private $response;
    
    public function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
    }

    public function json()
    {
        http_response_code($this->response->getStatusCode());

        header("Content-Type:{application/json}");
        header("charset:{utf-8}");

        echo $this->response->getBody();
        exit;
    }
}
