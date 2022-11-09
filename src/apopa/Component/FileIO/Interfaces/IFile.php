<?php

namespace Apopa\Component\FileIo\Interfaces;

interface IFile {
    public function asArray($path);
    public function asStdClass($path);
}