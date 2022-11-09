<?php

namespace Apopa\Repository;

use Apopa\Model\Author;
use Apopa\Model\Book;
use Apopa\Repository\Interfaces\IAuthors;

class AuthorsRepository extends MysqlAdapterRepository implements IAuthors {

    const TABLE_NAME = 'authors';

    public function getAll() {
        $result = $this->executor->execute(sprintf("
SELECT 
authors.id AS author_id, 
books.id AS book_id,
books.book AS book,
authors.author AS author
FROM %s LEFT JOIN books ON authors.id = books.author_id
", self::TABLE_NAME))->fetchAll();

        if($result) {
            $response = [];
            foreach($result as $item) {
                $author = new Author();
                $author->setAuthor($item['author']);
                $author->setId($item['author_id']);
                $book = new Book();
                $book->setId($item['book_id']);
                $book->setAuthor($author);
                $book->setName($item['book']);
                $author->addBook($book);
                $response[] = $author;
            }

            return $response;
        }

        return false;
    }

    public function findAuthorWithBooks($authorName) {
        $result = $this->executor->execute(sprintf("
SELECT 
authors.id AS author_id, 
books.id AS book_id,
books.book AS book,
authors.author AS author
FROM %s LEFT JOIN books ON authors.id = books.author_id
WHERE authors.author = :author_name
", self::TABLE_NAME),
            [
                ':author_name' => [
                    $authorName,
                    \PDO::PARAM_STR
                ],
            ])->fetchAll();

        if($result) {
            $author = new Author();
            $author->setAuthor($result[0]['author']);
            $author->setId($result[0]['author_id']);
            foreach($result as $item) {
                $book = new Book();
                $book->setId($item['book_id']);
                $book->setAuthor($author);
                $book->setName($item['book']);
                $author->addBook($book);
            }

            return $author;
        }

        return false;
    }

    public function findByAuthorName($authorName) {
        $result = $this->executor->execute(sprintf("SELECT * FROM %s WHERE author=:author", self::TABLE_NAME),
            [
                ':author' => [
                    $authorName,
                    \PDO::PARAM_STR
                ],
            ])->fetch();

        if($result) {
            $author = new Author();
            $author->setAuthor($result['author']);
            $author->setId($result['id']);

            return $author;
        }

        return false;
    }

    public function save(Author $author)
    {
        if(null === $author->getId()) {
            $author = $this->insert($author);
        } else {
            $this->update($author);
        }

        return $author;
    }

    private function update(Author $author)
    {
        $this->executor->execute(
            sprintf("UPDATE %s SET author=:author WHERE id=:id", self::TABLE_NAME),
            [
                ':author' => [
                    $author->getAuthor(),
                    \PDO::PARAM_STR
                ],
                ':id' => [
                    $author->getId(),
                    \PDO::PARAM_INT
                ],
            ]
        );
    }

    private function insert(Author $author): Author
    {
       $this->executor->execute(sprintf("INSERT INTO %s(id, author) VALUES(default, :author) ON DUPLICATE KEY UPDATE author=:author", self::TABLE_NAME),
       [
           ':author' => [
               $author->getAuthor(),
               \PDO::PARAM_STR
           ]
       ]);
       $author->setId($this->getLastInsertedId());

       return $author;
    }

    public function remove(Author $author)
    {
        return $this->executor
            ->execute(sprintf("DELETE FROM %s WHERE id=:id", self::TABLE_NAME),
                [
                    ':id' => [
                        $author->getId(),
                        \PDO::PARAM_INT
                    ],
                ]
            );
    }
}