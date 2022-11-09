<?php

namespace Apopa\Repository;

use Apopa\Component\DatabaseIO\DatabaseExecutor;

abstract  class MysqlAdapterRepository extends AbstractRepository {
    protected function getLastInsertedId() {
        $lastInsertId = $this->executor->execute("SELECT LAST_INSERT_ID()")->fetch(\PDO::FETCH_ASSOC);
        if(!empty($lastInsertId)) {
            return $lastInsertId['LAST_INSERT_ID()'];
        }

        throw new \Exception("unable to get last inserted id");
    }
}



