<?php

namespace Apopa\Repository;

use Apopa\Model\Author;
use Apopa\Model\Book;
use Apopa\Repository\Interfaces\IBooks;

class BooksRepository extends MysqlAdapterRepository implements IBooks
{

    const TABLE_NAME = 'books';

    public function findBookByNameAndAuthor(Book $book)
    {
        $result = $this->executor->execute(sprintf("SELECT * FROM %s WHERE book=:book AND author_id=:author_id", self::TABLE_NAME),
            [
                ':book' => [
                    $book->getName(),
                    \PDO::PARAM_STR,
                ],
                ':author_id' => [
                    $book->getAuthor()->getId(),
                    \PDO::PARAM_INT
                ]
            ]
        )
            ->fetch();

        if ($result) {
            $book = new Book();
            $book->setName($result['book']);
            $book->setId($result['id']);

            return $book;
        }

        return false;
    }

    public function save(Book $book)
    {
        if (null === $book->getId()) {
            $book = $this->insert($book);
        } else {
            $this->update($book);
        }

        return $book;
    }

    private function insert(Book $book): Book
    {
        $this->executor->execute(sprintf("INSERT INTO %s(id, book, author_id) VALUES(default, :book, %d)  ON DUPLICATE KEY UPDATE book=:book", self::TABLE_NAME, (int)$book->getAuthor()->getId()),
            [
                ':book' => [
                    $book->getName(),
                    \PDO::PARAM_STR,
                ],
            ]);
        $book->setId($this->getLastInsertedId());

        return $book;
    }

    private function update(Book $book)
    {
        $this->executor->execute(
            sprintf("UPDATE %s SET book=:book WHERE id=:id", self::TABLE_NAME),
            [
                ':book' => [
                    $book->getName(),
                    \PDO::PARAM_STR,
                ],
                ':id' => [
                    $book->getId(),
                    \PDO::PARAM_INT
                ]
            ]
        );
    }

    public function remove(Book $book)
    {
        return $this->executor
            ->execute(sprintf("DELETE FROM '%s' WHERE id=:id", self::TABLE_NAME),
                [
                    ':id' => [
                        $book->getId(),
                        \PDO::PARAM_INT
                    ]
                ]
            );
    }
}