<?php

namespace Apopa\Repository\Interfaces;

use Apopa\Model\Author;

interface IAuthors {
    public function save(Author $author);
    public function remove(Author $author);
}