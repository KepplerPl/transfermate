<?php

namespace Apopa\Component\FileIo;

use Apopa\Component\FileIo\Exceptions\DirectoryNotFoundException;

class RecursiveFileParser {
    public static function recursivelyGetAllFilesInDirectory($directoryPath) : array {
        if(!is_dir($directoryPath) || !file_exists( $directoryPath )) {
            throw new DirectoryNotFoundException();
        }

        $files = [];
        $di = new \RecursiveDirectoryIterator($directoryPath);
        foreach (new \RecursiveIteratorIterator($di) as $filename => $file) {
            if(!is_dir($filename)) {
                $files[] = $filename;
            }
        }

        return $files;
    }
}