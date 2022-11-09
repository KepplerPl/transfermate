<?php

namespace Apopa\Repository;

use Apopa\Component\DatabaseIO\DatabaseExecutor;

class AbstractRepository {
    protected $executor;

    public function __construct(DatabaseExecutor $databaseExecutor) {
        $this->executor = $databaseExecutor;
    }
}