<?php

namespace Apopa\Component\Parser;

use Apopa\Component\FileIo\Exceptions\FileNotFoundException;
use Apopa\Component\FileIo\Interfaces\IFile;
use Apopa\Component\Parser\Exceptions\InvalidJsonException;

class JsonParser implements IFile {

    private function parse($path, $asArray = false) {
        if(!file_exists($path)) {
            throw new FileNotFoundException();
        }

        $result = json_decode(file_get_contents($path), $asArray);

        if(json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidJsonException(json_last_error());
        }

        return $result;
    }

    public function asArray($path) : array
    {
        return $this->parse($path, true);
    }

    public function asStdClass($path)
    {
        return $this->parse($path);
    }
}