<?php

namespace Apopa\Component\Parser;

use Apopa\Component\FileIo\Exceptions\FileNotFoundException;
use Apopa\Component\Parser\Exceptions\InvalidXMLException;
use Apopa\Component\Parser\Exceptions\NodeNotFoundException;

class SimpleXMLParser {

    private $contents;

    public function parse($path) {
        if(!file_exists($path)) {
            throw new FileNotFoundException();
        }
        libxml_use_internal_errors(true);
        $this->contents = simplexml_load_file($path);
        if($this->contents === false) {
            $errorMessages = [];
            foreach(libxml_get_errors() as $error) {
                $errorMessages[] = $error->message;
            }

            throw new InvalidXMLException("Unable to process XML | libxml_use_internal_errors are: " . json_encode($errorMessages));
        }

        return $this;
    }

    public function getNode($name) {
        if(property_exists($this->contents, $name)) {
            $string = (string)$this->contents->{$name}[0];

            return !empty($string) ? $string : false;
        }

        return false;
    }
}