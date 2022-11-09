<?php

namespace Apopa\Repository\Interfaces;

use Apopa\Model\Book;

interface IBooks{
    public function save(Book $book);
    public function remove(Book $book);
}