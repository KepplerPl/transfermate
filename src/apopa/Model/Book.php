<?php

namespace Apopa\Model;

use Apopa\Component\Interfaces\Arrayable;
use Apopa\Component\Interfaces\Jsonable;

class Book implements Jsonable, Arrayable {
    private $id;
    private $name;

    /**
     * @var Author
     */
    private $author;

    public function toArray()
    {
        return $this->getRepresentation();
    }

    private function getRepresentation() {
        return [
            'name' => $this->name,
            'author' => $this->author->getAuthor()
        ];
    }

    public function toJson()
    {
        return json_encode($this->getRepresentation());
    }

    /**
     * @return mixed
     */
    public function getAuthor(): Author
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor( Author $author)
    {
        $this->author = $author;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}