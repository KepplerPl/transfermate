<?php

namespace Apopa\Component\DatabaseIO;

use Apopa\Component\DatabaseIO\Interfaces\IConnection;

class DatabaseExecutor {

    private $conn;

    public function __construct(IConnection $conn) {
        $this->conn = $conn;
    }

    public function execute($query, array $params = []) {
        $sth = $this->conn->getConnection()->prepare($query);
        if(!empty($params)) {
            foreach($params as $key => $value) {
                $sth->bindValue($key, $value[0], $value[1]);
            }
        }
        $sth->execute();

        return $sth;
    }
}