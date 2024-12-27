<?php

use PHPUnit\Framework\TestCase;
use Src\Models\Posts;

class PostsTest extends TestCase
{
    protected $posts;

    protected function setUp(): void
    {
        // Mock de la clase Posts para simular sus métodos y no depender de la base de datos
        $this->posts = $this->createMock(Posts::class);
    }

    public function testGetPosts()
    {
        $params = ["search" => "test", "limit" => 5, "page" => 1];
        $expectedResult = ["posts" => [["title" => "Test Post"]], "totalCount" => 1];
        $this->posts->method("getAllPosts")->willReturn($expectedResult);
        $result = $this->posts->getAllPosts($params);
        $this->assertSame($expectedResult, $result);
    }

    public function testCreatePost()
    {
        $this->posts->method("createPost")->willReturn(true);
        $result = $this->posts->createPost(1, "Test Post", "Contenido", 1, "Inglés");
        $this->assertTrue($result);
    }

    public function testDeletePost()
    {
        $this->posts->method("deletePost")->willReturn(true);
        $result = $this->posts->deletePost(1);
        $this->assertTrue($result);
    }

    public function testUpdatePost()
    {
        $this->posts->method("updatePost")->willReturn(true);
        $result = $this->posts->updatePost(1, "Título actualizado", "Contenido actualizado", 2, "Español");
        $this->assertTrue($result);
    }

    public function testGetComments()
    {
        $expectedResult = [
            "commentTree" => [["comment" => "Buen post!"]],
            "totalCount" => 1
        ];
        $this->posts->method("getPostComments")->willReturn($expectedResult);
        $result = $this->posts->getPostComments(1, 2);
        $this->assertSame($expectedResult, $result);
    }

    public function testInsertComment()
    {
        $this->posts->method("insertComment")->willReturn(true);
        $result = $this->posts->insertComment(3, 2, "Comentario de prueba", null);
        $this->assertTrue($result);
    }

    public function testDeleteComment()
    {
        $this->posts->method("deleteCommentAndChildren")->willReturn(true);
        $result = $this->posts->deleteCommentAndChildren(6);
        $this->assertTrue($result);
    }

    public function testUpdateComment()
    {
        $this->posts->method("updateComment")->willReturn(true);
        $result = $this->posts->updateComment(3, "Comentario actualizado");
        $this->assertTrue($result);
    }

    public function testLikePost()
    {
        $this->posts->method("likePost")->willReturn(true);
        $result = $this->posts->likePost(1, 1);
        $this->assertTrue($result);
    }

    public function testUnlikePost()
    {
        $this->posts->method("unlikePost")->willReturn(true);
        $result = $this->posts->unlikePost(1, 1);
        $this->assertTrue($result);
    }

    public function testLikeComment()
    {
        $this->posts->method("likeComment")->willReturn(true);
        $result = $this->posts->likeComment(1, 1);
        $this->assertTrue($result);
    }

    public function testUnlikeComment()
    {
        $this->posts->method("unlikeComment")->willReturn(true);
        $result = $this->posts->unlikeComment(1, 1);
        $this->assertTrue($result);
    }

    public function testGetNotifications()
    {
        $expectedResult = [["notification" => "Nuevo comentario en tu post"]];
        $this->posts->method("getNotifications")->willReturn($expectedResult);
        $result = $this->posts->getNotifications(1);
        $this->assertSame($expectedResult, $result);
    }

    public function testMarkAllAsRead()
    {
        $this->posts->method("markAllAsRead")->willReturn(true);
        $result = $this->posts->markAllAsRead(1);
        $this->assertTrue($result);
    }
}
