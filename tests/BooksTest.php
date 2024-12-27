<?php

use PHPUnit\Framework\TestCase;
use Src\Models\Books;

class BooksTest extends TestCase
{
    protected $books;

    protected function setUp(): void
    {
        // Mock de la clase Books para simular sus métodos y no depender de la base de datos
        $this->books = $this->createMock(Books::class);
    }

    public function testGetBooks()
    {
        $expectedResult = ["books" => [["title" => "Deep Work"]], "total" => 1];
        $this->books->method("getBooks")->willReturn($expectedResult);
        $result = $this->books->getBooks(["search" => "Deep Work"]);
        $this->assertSame($expectedResult, $result);
    }

    public function testAddBook()
    {
        $expectedId = 123;
        $this->books->method("addBook")->willReturn(["id" => $expectedId]);
        $result = $this->books->addBook("Nuevo libro", 1, 2, 3, 200, 2024, "cover.jpg", [], "Español", "Descripción", "1234567890");
        $this->assertSame($expectedId, $result["id"]);
    }

    public function testUpdateBook()
    {
        $this->books->method("updateBook")->willReturn(true);
        $result = $this->books->updateBook(15, "Libro actualizado", 3, 2, 1, 100, 2018, "updated_cover.jpg", "www.test.com", "Inglés", "Nueva descripción", "0987654321");
        $this->assertTrue($result);
    }

    public function testDeleteBook()
    {
        $this->books->method("deleteBook")->willReturn(true);
        $result = $this->books->deleteBook(1);
        $this->assertTrue($result);
    }
}
