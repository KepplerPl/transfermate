<?php

namespace Apopa\Controller;

use Apopa\Component\Config\ConfigReader;
use Apopa\Component\DatabaseIO\DatabaseExecutor;
use Apopa\Component\DatabaseIO\MysqlConnection;
use Apopa\Component\Http\SimpleJsonResponse;
use Apopa\Component\Parser\JsonParser;
use Apopa\Model\Author;
use Apopa\Repository\AuthorsRepository;

class DefaultController {

    private $authorRepository;

    public function __construct() {
        $fileReader = new JsonParser();
        $config = new ConfigReader($fileReader);
        $connection = MysqlConnection::getInstance($config);
        $this->authorRepository = new AuthorsRepository(new DatabaseExecutor($connection));
    }

    public function search() {
        $response = new SimpleJsonResponse();
        $response->setHeaders(['Content-type' => 'application/json']);
        if(!isset($_GET['q']) || "" == trim($_GET['q'])) {
            $response->setResponseCode(200);
            $response->setBody(['missing search term']);
            $response->send();
        }

        $searchTerm = trim($_GET['q']);// no sanitization, assume we're ok..famous last words

        $result = $this->authorRepository->findAuthorWithBooks($searchTerm);
        $response->setResponseCode(200);
        if($result) {
            $response->setBody($result->toArray());
        } else {
            $response->setBody("no results");
        }

        $response->send();
    }

    public function getAll() {
        $response = new SimpleJsonResponse();
        $response->setHeaders(['Content-type' => 'application/json']);

        $result = $this->authorRepository->getAll();
        $response->setResponseCode(200);
        if($result) {
            $representation = [];
            foreach($result as $author) {
                /**
                 * @var $author Author
                 */
                $representation[] = $author->toArray();
            }
            $response->setBody($representation);
        } else {
            $response->setBody("no results");
        }

        $response->send();
    }

}