<?php

use Apopa\Component\Config\ConfigReader;
use Apopa\Component\DatabaseIO\DatabaseExecutor;
use Apopa\Component\DatabaseIO\MysqlConnection;
use Apopa\Component\FileIo\RecursiveFileParser;
use Apopa\Component\Parser\Exceptions\InvalidXMLException;
use Apopa\Component\Parser\JsonParser;
use Apopa\Component\Parser\SimpleXMLParser;
use Apopa\Model\Author;
use Apopa\Model\Book;
use Apopa\Repository\AuthorsRepository;
use Apopa\Repository\BooksRepository;

require_once __DIR__ . "/autoloader.php";

const FINISHED_WITHOUT_ERRORS = 0;
const FINISHED_WITH_ERRORS = 1;

// this could have been solved with a di container but i didn't think it's necessary for this
$fileReader = new JsonParser();
$config = new ConfigReader($fileReader);
$connection = MysqlConnection::getInstance($config);
$databaseExecutor = new DatabaseExecutor($connection);

$booksRepo = new BooksRepository($databaseExecutor);
$authorsRepo = new AuthorsRepository($databaseExecutor);

$filesToParse = RecursiveFileParser::recursivelyGetAllFilesInDirectory(__DIR__ . "/dummy_data");
$xmlParser = new SimpleXMLParser();

if(empty($filesToParse)) {
    echo "No files, exiting";
    die;
}

$status = FINISHED_WITHOUT_ERRORS;

foreach ($filesToParse as $file) {
    if(substr($file, -3) !== 'xml') {
        continue;
    }
    try{
        $xmlParser->parse($file);
    }catch (InvalidXMLException $exception) {
        // log error and continue
        error_log($exception->getMessage());
        $status = FINISHED_WITH_ERRORS;
        continue;
    }
    $authorName = $xmlParser->getNode('author');
    if($authorName) {
        $author = new Author();
        $author->setAuthor($authorName);
        $existingAuthor = $authorsRepo->findByAuthorName($author->getAuthor());
        if($existingAuthor instanceof Author) {
            $author = $existingAuthor;
        }
        $authorsRepo->save($author);

        $bookName = $xmlParser->getNode('name');
        if($bookName) {
            $book = new Book();
            $book->setName($bookName);
            $book->setAuthor($author);
            $existingBook = $booksRepo->findBookByNameAndAuthor($book);
            if($existingBook instanceof Book) {
                $book = $existingBook;
            }

            $booksRepo->save($book);
        }
    }
}

switch($status) {
    case FINISHED_WITH_ERRORS:
        echo "Cron job finished successfully with errors. See error log for further info";
        break;
    case FINISHED_WITHOUT_ERRORS:
        echo "Cron job finished successfully without errors";
        break;
    default:
        echo "Cron job finished";
        break;
}

