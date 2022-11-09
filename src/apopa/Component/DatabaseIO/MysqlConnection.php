<?php

namespace Apopa\Component\DatabaseIO;

use Apopa\Component\Config\Interfaces\IConfig;
use Apopa\Component\DatabaseIO\Interfaces\IConnection;

class MysqlConnection implements IConnection {
    private static $instance;
    private $config;
    private $conn;

    private function __construct(IConfig $config) {
        $this->config = $config->get('database');

        $this->conn = new \PDO("mysql:host={$this->config['servername']};dbname={$this->config['dbname']}", $this->config['username'],$this->config['password'],
            array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

    }

    public static function getInstance(IConfig $config)
    {
        if(!self::$instance)
        {
            self::$instance = new MysqlConnection($config);
        }

        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->conn;
    }
}
