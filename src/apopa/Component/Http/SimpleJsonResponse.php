<?php

namespace Apopa\Component\Http;

class SimpleJsonResponse extends SimpleResponse {
    public function send() {
        ob_start();
        http_response_code($this->code);
        foreach($this->headers as $headerName => $header) {
            header($headerName .":".$header);
        }
        echo json_encode($this->body);
        ob_end_flush();
        die;
    }
}