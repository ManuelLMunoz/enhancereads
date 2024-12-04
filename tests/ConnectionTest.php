<?php

use PHPUnit\Framework\TestCase;
use Src\Models\Connection;

class ConnectionTest extends TestCase
{
    protected $connection;

    protected function setUp(): void
    {
        // Mocking de la clase Connection para simular el método "connection"
        $this->connection = $this->getMockBuilder(Connection::class)
            ->onlyMethods(["connection"])
            ->getMock();
    }

    public function testConnectionEstablished()
    {
        $this->connection->method("connection")
            ->willReturn(new PDO("mysql:host=localhost;dbname=enhancereads_test", "root", "V1O=z6N^6=wv"));

        $this->assertInstanceOf(PDO::class, $this->connection->connection());
    }

    public function testConnectionFails()
    {
        $this->connection->method("connection")
            ->will($this->throwException(new PDOException("Error de conexión")));

        $this->expectException(PDOException::class);
        $this->connection->connection();
    }
}
