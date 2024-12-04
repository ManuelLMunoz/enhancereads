<?php

use PHPUnit\Framework\TestCase;
use Src\Models\Books;
use PDO;
use PDOStatement;

class BooksTest extends TestCase
{
    private $mockConnection;
    private $booksModel;

    protected function setUp(): void
    {
        // Crear un mock para la conexión PDO
        $this->mockConnection = $this->createMock(PDO::class);
        // Crear una instancia de Books, pasando la conexión mockeada
        $this->booksModel = new Books($this->mockConnection);
    }

    public function testGetBooks()
    {
        $params = [
            'search' => 'arte de la guerra',
            'limit' => 10,
            'page' => 1,
            'order' => 'ASC',
            'author' => [1],
            'genre' => [2],
            'publisher' => [3],
            'language' => ['en'],
            'pages' => 100
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetchAll')->willReturn([['id' => 1, 'title' => 'PHP for Beginners']]);
        $stmtMock->method('fetch')->willReturn(['total' => 1]);

        $this->mockConnection->method('prepare')->willReturn($stmtMock);

        $result = $this->booksModel->getBooks($params);
        
        $this->assertArrayHasKey('books', $result);
        $this->assertArrayHasKey('total', $result);

        $this->assertCount(1, $result['books']);
        $this->assertEquals('PHP for Beginners', $result['books'][0]['title']);
        $this->assertEquals(1, $result['total']);
    }

    public function testGetAuthors()
    {
        $authorsMock = [
            ['id' => 1, 'name' => 'Author One'],
            ['id' => 2, 'name' => 'Author Two']
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('fetchAll')->willReturn($authorsMock);
        $this->mockConnection->method('query')->willReturn($stmtMock);
        $authors = $this->booksModel->getAuthors();

        $this->assertCount(2, $authors);
        $this->assertEquals('Author One', $authors[0]['name']);
        $this->assertEquals('Author Two', $authors[1]['name']);
    }

    public function testAddBook()
    {
        $bookData = [
            'title' => 'PHP Testing',
            'authorId' => 1,
            'genreId' => 2,
            'publisherId' => 3,
            'pages' => 200,
            'year' => 2024,
            'cover' => 'php_testing_cover.jpg',
            'link' => ['link1', 'link2'],
            'language' => 'en',
            'description' => 'A book about PHP testing.',
            'isbn' => '123-456-789'
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $this->mockConnection->method('prepare')->willReturn($stmtMock);

        $result = $this->booksModel->addBook(
            $bookData['title'], $bookData['authorId'], $bookData['genreId'], $bookData['publisherId'], $bookData['pages'],
            $bookData['year'], $bookData['cover'], $bookData['link'], $bookData['language'], $bookData['description'], $bookData['isbn']
        );

        $this->assertArrayHasKey('id', $result);
    }

    public function testUpdateBook()
    {
        $bookData = [
            'id' => 1,
            'title' => 'Updated Book Title',
            'author' => 1,
            'genre' => 2,
            'publisher' => 3,
            'pages' => 250,
            'year' => 2025,
            'cover' => 'updated_cover.jpg',
            'link' => ['updated_link1', 'updated_link2'],
            'language' => 'en',
            'description' => 'Updated description.',
            'isbn' => '987-654-321'
        ];

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $this->mockConnection->method('prepare')->willReturn($stmtMock);

        $result = $this->booksModel->updateBook(
            $bookData['id'], $bookData['title'], $bookData['author'], $bookData['genre'], $bookData['publisher'],
            $bookData['pages'], $bookData['year'], $bookData['cover'], $bookData['link'], $bookData['language'],
            $bookData['description'], $bookData['isbn']
        );
        $this->assertTrue($result);
    }

    public function testDeleteBook()
    {
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $this->mockConnection->method('prepare')->willReturn($stmtMock);
        $result = $this->booksModel->deleteBook(1);
        $this->assertTrue($result);
    }
}