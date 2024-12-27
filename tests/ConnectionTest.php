<?php

use PHPUnit\Framework\TestCase;
use Src\Models\Connection;

class ConnectionTest extends TestCase
{
    protected $connection;

    protected function setUp(): void
    {
        // Mock de la clase Connection
        $this->connection = $this->createMock(Connection::class);
    }

    public function testConnection()
    {
        $pdoMock = $this->createMock(PDO::class);
        $this->connection->method("connection")->willReturn($pdoMock);
        $this->assertInstanceOf(PDO::class, $this->connection->connection());
    }

    public function testConnectionFails()
    {
        $this->connection->method("connection")->will($this->throwException(new PDOException("Error de conexión")));
        $this->expectException(PDOException::class);
        $this->connection->connection();
    }
}
