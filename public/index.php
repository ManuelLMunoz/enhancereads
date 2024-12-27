<?php

require_once "../vendor/autoload.php";

use Src\Route;
use Src\Controllers\{
    AccountController,
    BooksController,
    GoogleController,
    PageController,
    PostController
};

//---------------
// Rutas de index
//---------------
foreach (["/", "index"] as $route) {
    Route::get($route, [PageController::class, "index"]);
}

//---------------
// Rutas de legal
//---------------
Route::get("terms-of-use", [PageController::class, "terms"]);
Route::get("privacy-policy", [PageController::class, "privacy"]);
//----------------------------
// Rutas de las notificaciones
//----------------------------
Route::get("get-notifications", [PostController::class, "getNotifications"]);
Route::post("mark-all-notifications-read", [PostController::class, "markAllAsRead"]);

//-----------------
// Rutas de account
//-----------------
Route::get("account", [AccountController::class, "account"]);

foreach (["login", "register", "reset-password"] as $route) {
    Route::get($route, fn() => header("Location: /account") && exit());
}

// Opciones de cuenta
Route::post("login", [AccountController::class, "login"]);
Route::post("register", [AccountController::class, "register"]);
Route::post("reset-password", [AccountController::class, "resetPassword"]);
Route::get("logout", [AccountController::class, "logout"]);
Route::get("profile", [AccountController::class, "profile"]);
Route::post("update-profile", [AccountController::class, "updateProfile"]);
Route::post("delete-account", [AccountController::class, "deleteAccount"]);

// 2FA
Route::get("2FA-verification", [AccountController::class, "admin2FA"]);
Route::post("verify-2FA", [AccountController::class, "verify2FA"]);

//-------------------------------
// Rutas para la cuenta de Google
//-------------------------------
Route::get("google-login", [GoogleController::class, "login"]);
Route::get("google-callback", [GoogleController::class, "callback"]);
Route::get("google-logout", [GoogleController::class, "logout"]);

//---------------
// Rutas de books
//---------------

// Página principal
foreach (["books", "books/page={page}"] as $route) {
    Route::get($route, [BooksController::class, "books"]);
}
// Detalles de un libro
Route::get("books/{id}/{title}", [BooksController::class, "viewBookDetails"]);

// Administración de libros
Route::get("manage-books", [BooksController::class, "manageBook"]);
foreach (["add-book", "add-author", "add-genre", "add-publisher"] as $route) {
    Route::get($route, fn() => header("Location: /manage-books") && exit());
}

// Gestión de libros
Route::post("fetch-books", [BooksController::class, "fetchBooks"]);
Route::post("fetch-authors-genres-and-publishers", [BooksController::class, "fetchAuthorsGenresAndPublishers"]);
Route::get("edit-books/{id}", [BooksController::class, "editBooks"]);
Route::post("update-book", [BooksController::class, "updateBook"]);
Route::post("add-book", [BooksController::class, "addBook"]);
Route::post("add-author", [BooksController::class, "addAuthor"]);
Route::post("add-genre", [BooksController::class, "addGenre"]);
Route::post("add-publisher", [BooksController::class, "addPublisher"]);
Route::post("delete-book/{id}", [BooksController::class, "deleteBook"]);

//---------------
// Rutas de posts
//---------------

// Página principal
foreach (["posts", "posts/page={page}"] as $route) {
    Route::get($route, [PostController::class, "posts"]);
}
// Detalles de un post
Route::get("posts/{id}/{title}", [PostController::class, "viewPostDetails"]);

// Opciones de los posts
Route::post("fetch-posts", [PostController::class, "fetchPosts"]);
Route::post("get-genres", [PostController::class, "getGenres"]);
Route::post("like-post", [PostController::class, "likePost"]);
Route::post("create-post", [PostController::class, "createPost"]);
Route::post("delete-post/{id}", [PostController::class, "deletePost"]);
Route::post("edit-post/{id}", [PostController::class, "updatePost"]);

// Opciones de los comentarios
Route::post("fetch-comments", [PostController::class, "fetchComments"]);
Route::post("like-comment", [PostController::class, "likeComment"]);
Route::post("submit-comment", [PostController::class, "submitComment"]);
Route::post("delete-comment/{id}", [PostController::class, "deleteComment"]);
Route::post("edit-comment/{id}", [PostController::class, "updateComment"]);

//---------------------------------------------------------------------
// Despachador para interpretar y generear las respuestas del enrutador
//---------------------------------------------------------------------
Route::dispatch();
