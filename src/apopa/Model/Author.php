<?php

namespace Apopa\Model;

use Apopa\Component\Interfaces\Arrayable;
use Apopa\Component\Interfaces\Jsonable;

class Author implements Jsonable, Arrayable {
    private $id;
    private $author;

    private $books = [];

    public function toArray()
    {
        return $this->getRepresentation();
    }

    private function getRepresentation(){
        $representation = [
            'author' => $this->author
        ];

        foreach($this->books as $book) {
            $representation[] = [
                'book' => $book->getName()
            ];
        }

        return $representation;
    }

    public function toJson()
    {
        return json_encode($this->getRepresentation());
    }

    public function addBook(Book $book) {
        $this->books[] = $book;
    }

    public function removeBook(Book $book) {
        foreach($this->books as $key => $existingBook) {
            if($book->getId() == $existingBook->getId()) {
                unset($this->books[$key]);
                break;
            }
        }
    }

    /**
     * @return array
     */
    public function getBooks(): array
    {
        return $this->books;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }
}