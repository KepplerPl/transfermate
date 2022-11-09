<?php

// simple routing mechanism
use Apopa\Controller\DefaultController;

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
    case "/":
        header("no-cache: no-cache");
        echo file_get_contents(__DIR__."/../../assets/default.html");
        die;
    case "/api/v1/search":
        $controller = new DefaultController();
        $controller->search();
        break;
    case "/api/v1/all":
        $controller = new DefaultController();
        $controller->getAll();
        break;
    default:
        http_response_code(404);
        echo json_encode("404");
        break;
}
