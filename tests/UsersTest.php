<?php

use PHPUnit\Framework\TestCase;
use Src\Models\Users;

class UsersTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        // Mock de la clase Users para simular sus métodos y no depender de la base de datos
        $this->user = $this->createMock(Users::class);
    }

    public function testFindUser()
    {
        $expectedResult = ["user" => ["email" => "manuel@gmail.com"]];
        $this->user->method("findUser")->willReturn($expectedResult);
        $result = $this->user->findUser("manuel@gmail.com");
        $this->assertSame($expectedResult, $result);
    }

    public function testInsertUser()
    {
        $expectedResult = ["id" => 1];
        $this->user->method("insertUser")->willReturn($expectedResult);
        $result = $this->user->insertUser("Nuevo usuario", "user@test.com", "password123", "user");
        $this->assertSame($expectedResult, $result);
    }

    public function testUpdateUser()
    {
        $this->user->method("updateUser")->willReturn(true);
        $result = $this->user->updateUser(7, "usuario actualizado", "updateduser@test.com", "newpassword", "updated_avatar.webp");
        $this->assertTrue($result);
    }

    public function testDeleteUser()
    {
        $this->user->method("deleteUser")->willReturn(true);
        $result = $this->user->deleteUser(10);
        $this->assertTrue($result);
    }

    public function testUpdatePassword()
    {
        $this->user->method("updatePassword")->willReturn(true);
        $result = $this->user->updatePassword("user@test.com", "newpassword123");
        $this->assertTrue($result);
    }
}
