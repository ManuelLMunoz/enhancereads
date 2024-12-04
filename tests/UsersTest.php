<?php

use PHPUnit\Framework\TestCase;
use Src\Models\Users;
use PDO;
use PDOStatement;

class UsersTest extends TestCase
{
    private $pdoMock;
    private $users;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->users = new Users($this->pdoMock);
    }

    // Prueba de 'findUser'
    public function testFindUser()
    {
        $login = 'testUser';
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(['id' => 1, 'user' => $login]);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $result = $this->users->findUser($login);
        $this->assertNotNull($result);
        $this->assertEquals($login, $result['user']);
    }

    // Prueba de 'findUserByEmail'
    public function testFindUserByEmail()
    {
        $email = 'test@example.com';
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetch')->willReturn(['id' => 1, 'email' => $email]);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $result = $this->users->findUserByEmail($email);
        $this->assertNotNull($result);
        $this->assertEquals($email, $result['email']);
    }

    // Prueba de 'getUserRole'
    public function testGetUserRole()
    {
        $email = 'test@example.com';
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $stmtMock->method('fetchColumn')->willReturn('admin');

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $role = $this->users->getUserRole($email);
        $this->assertEquals('admin', $role);
    }

    // Prueba de 'insertUser'
    public function testInsertUser()
    {
        $user = 'testUser';
        $email = 'test@example.com';
        $password = 'password123';
        $role = 'user';

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);
        $this->pdoMock->method('prepare')->willReturn($stmtMock);
        $this->pdoMock->method('lastInsertId')->willReturn(1);

        $result = $this->users->insertUser($user, $email, $password, $role);
        $this->assertEquals(1, $result);
    }

    // Prueba de 'deleteUser'
    public function testDeleteUser()
    {
        $id = 1;
        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $result = $this->users->deleteUser($id);
        $this->assertTrue($result);
    }

    // Prueba de 'updateUser'
    public function testUpdateUser()
    {
        $id = 1;
        $user = 'newUser';
        $email = 'newemail@example.com';
        $password = 'newpassword123';
        $avatar = 'newAvatar.svg';

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $result = $this->users->updateUser($id, $user, $email, $password, $avatar);
        $this->assertTrue($result);
    }

    // Prueba de 'updatePassword'
    public function testUpdatePassword()
    {
        $email = 'test@example.com';
        $newPassword = 'newpassword123';

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $result = $this->users->updatePassword($email, $newPassword);
        $this->assertTrue($result);
    }

    // Prueba de 'update2FASecret'
    public function testUpdate2FASecret()
    {
        $userId = 1;
        $secret = 'secretCode';

        $stmtMock = $this->createMock(PDOStatement::class);
        $stmtMock->method('execute')->willReturn(true);

        $this->pdoMock->method('prepare')->willReturn($stmtMock);

        $result = $this->users->update2FASecret($userId, $secret);
        $this->assertTrue($result);
    }
}
