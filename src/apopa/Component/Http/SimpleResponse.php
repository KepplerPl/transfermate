<?php

namespace Apopa\Component\Http;

class SimpleResponse {

    protected $body;
    protected $headers;
    protected $code;

    public function setResponseCode($code) {
        $this->code = $code;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public function setHeaders(array $headers) {
        $this->headers = $headers;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
    
    public function getCode()
    {
        return $this->code;
    }
}