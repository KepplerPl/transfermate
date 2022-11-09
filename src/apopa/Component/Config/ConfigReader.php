<?php

namespace Apopa\Component\Config;

use Apopa\Component\Config\Exceptions\ConfigNotFoundException;
use Apopa\Component\Config\Interfaces\IConfig;
use Apopa\Component\FileIo\Interfaces\IFile;

class ConfigReader implements IConfig {

    private $config = [];

    public function __construct(IFile $reader) {
        $this->config = $reader->asArray(__DIR__."/../../config.json");
    }

    public function get($param)
    {
        if(isset($this->config[$param])) {
            return $this->config[$param];
        }

        throw new ConfigNotFoundException();
    }
}
