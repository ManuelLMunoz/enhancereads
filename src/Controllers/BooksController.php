<?php

namespace Src\Controllers;

use Src\Models\Books;

class BooksController extends Controller
{
    public function books($page = 1)
    {
        return $this->view("books/books", ["page" => $page]);
    }

    public function manageBook()
    {
        return $this->view("books/manage-books");
    }

    public function viewBookDetails($id, $title)
    {
        $book = (new Books())->getBookById($id);

        // Verificar si el libro existe y si el título coincide con el formateado
        if (!$book || $this->sanitizeTitle($book["title"]) !== $title) {
            return $this->view("404", [], 404);
        }

        return $this->view("books/book-details", ["book" => $book]);
    }

    private function sanitizeTitle($title)
    {
        // Eliminar acentos y caracteres especiales, convertir a minúsculas y reemplazar espacios por guiones
        $unaccented = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $title);
        $sanitized = preg_replace("/[^a-z0-9 ]/", "", strtolower($unaccented));
        return str_replace(" ", "-", trim($sanitized));
    }


    // ---------------------------------------------
    // Obtener y filtrar los libros según parámetros
    // ---------------------------------------------
    public function fetchBooks()
    {
        // Parámetros de búsqueda
        $params = [
            "search" => $_POST["query"] ?? "",
            "order" => $_POST["order"] ?? "asc",
            "limit" => 10,  // Nº de elementos a mostrar por página
            "page" => max(1, intval($_POST["page"] ?? 1)),
            "author" => $_POST["author"] ?? [],
            "genre" => $_POST["genre"] ?? [],
            "publisher" => $_POST["publisher"] ?? [],
            "language" => $_POST["language"] ?? [],
            "year" => $_POST["year"] ?? "",
            "pages" => $_POST["pages"] ?? ""
        ];

        // Obtener los libros y la información de paginación
        $booksData = (new Books())->getBooks($params);
        $books = $booksData["books"];
        $totalRows = $booksData["total"];
        $totalPages = ceil($totalRows / $params["limit"]);
        $page = max(min($params["page"], $totalPages), 1);
        $startElement = ($page - 1) * $params["limit"] + 1;
        $endElement = min($page * $params["limit"], $totalRows);

        // Renderizar la vista de los libros
        ob_start();
        include(__DIR__ . "/../views/books/fetch_books.php");
        $output = ob_get_clean();

        // Respuesta con los datos en formato JSON
        $book = new Books();
        echo json_encode([
            "html" => $output,
            "filters" => [
                "authors" => $book->getAuthors(),
                "genres" => $book->getGenres(),
                "publishers" => $book->getPublishers(),
                "languages" => $book->getLanguages(),
                "pages" => ["50", "100", "200", "500"]
            ],
            "pagination" => [
                "total_pages" => $totalPages,
                "current_page" => $page,
                "start_element" => $startElement,
                "end_element" => $endElement,
                "total_row" => $totalRows
            ]
        ]);
    }

    public function fetchAuthorsGenresAndPublishers()
    {
        $books = new Books();
        echo json_encode([
            "success" => true,
            "authors" => $books->getAllAuthors(),
            "genres" => $books->getAllGenres(),
            "publishers" => $books->getAllPublishers()
        ]);
    }

    // ------------------------------
    // Subida de la imagen de portada
    // ------------------------------
    private function handleCoverUpload($cover, $bookId, $existingCoverName = null)
    {
        if ($cover["error"] === UPLOAD_ERR_NO_FILE) {
            return $existingCoverName;
        }

        // Comprobar errores de subida, tamaño y extensión
        if ($cover["error"] !== UPLOAD_ERR_OK) {
            return ["success" => false, "message" => "Error al subir el archivo. Pruebe de nuevo"];
        }
        if ($cover["size"] > 1048576) {
            return ["success" => false, "message" => "El tamaño máximo de imagen permitido es 1MB"];
        }
        $extension = strtolower(pathinfo($cover["name"], PATHINFO_EXTENSION));
        if (!in_array($extension, ["jpg", "jpeg", "png", "webp"])) {
            return ["success" => false, "message" => "Solo se permiten archivos con extensión: jpg, jpeg, png y webp"];
        }

        // Intentar mover el archivo y devolver el resultado
        $coverNewName = "cover_book_" . $bookId . ".webp";
        $destPath = __DIR__ . "/../../public/assets/img/books/" . $coverNewName;
        return move_uploaded_file($cover["tmp_name"], $destPath)
            ? ["success" => true, "coverName" => $coverNewName]
            : ["success" => false, "message" => "Error al mover el archivo"];
    }

    // -------------
    // Agregar datos
    // -------------
    public function addBook()
    {
        $formData = $_POST;

        $book = (new Books())->addBook(
            htmlspecialchars($formData["title"]),
            htmlspecialchars($formData["author"]),
            htmlspecialchars($formData["genre"]),
            htmlspecialchars($formData["publisher"]),
            htmlspecialchars($formData["pages"]),
            htmlspecialchars($formData["year"]),
            null, // No tenemos la portada aún
            $formData["links"],
            htmlspecialchars($formData["language"]),
            htmlspecialchars($formData["description"]),
            htmlspecialchars($formData["isbn"])
        );

        if (!$book) {
            return $this->view("books/manage-books", ["error" => "Error al agregar el libro", "formData" => $formData]);
        }

        // Comprobar si hay una portada
        if (!empty($_FILES["cover"]) && $_FILES["cover"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleCoverUpload($_FILES["cover"], $book["id"]);

            if (empty($uploadResult["success"])) {
                // Si hay un error en la portada, eliminar el libro
                (new Books())->deleteBook($book["id"]);
                return $this->view("books/manage-books", ["error" => $uploadResult["message"] ?? "Error al subir la portada", "formData" => $formData]);
            }

            // Si la portada es válida, actualizar el libro
            (new Books())->updateBookCover($book["id"], $uploadResult["coverName"]);
        }

        return $this->view("books/manage-books", ["success" => "Libro agregado con éxito"]);
    }

    public function addAuthor()
    {
        $author = (new Books())->addAuthor(htmlspecialchars($_POST["new_author"]));
        return $this->view("books/manage-books", $author ? ["success" => "Autor agregado con éxito"] : ["error" => "El autor ya existe"]);
    }

    public function addGenre()
    {
        $genre = (new Books())->addGenre(htmlspecialchars($_POST["new_genre"]));
        return $this->view("books/manage-books",  $genre ? ["success" => "Género agregado con éxito"] : ["error" => "El género ya existe"]);
    }

    public function addPublisher()
    {
        $publisher = (new Books())->addPublisher(htmlspecialchars($_POST["new_publisher"]));
        return $this->view("books/manage-books",  $publisher ? ["success" => "Editorial agregada con éxito"] : ["error" => "La editorial ya existe"]);
    }


    // --------------------------
    // Editar y actualizar libros
    // --------------------------
    public function editBooks($id)
    {
        $books = new Books();
        $book = $books->getBookById($id);

        if (!$book) {
            header("Location: /");
            exit();
        }

        return $this->view("books/edit-books", [
            "book" => $book,
            "authors" => $books->getAuthors(),
            "genres" => $books->getGenres(),
            "publishers" => $books->getPublishers()
        ]);
    }

    public function updateBook()
    {
        $id = $_POST["id"] ?? null;
        if (!$id) {
            echo json_encode(["success" => false, "message" => "ID del libro no proporcionado"]);
            return;
        }

        $currentBook = (new Books())->getBookById($id);
        if (!$currentBook) {
            echo json_encode(["success" => false, "message" => "Libro no encontrado"]);
            return;
        }

        $coverName = $currentBook["cover"];
        $updateSuccessful = false;
        $message = "Error al actualizar el libro";

        // Manejar la actualización de la portada si se ha enviado un nuevo archivo
        if (isset($_FILES["cover"]) && $_FILES["cover"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $coverUploadResult = $this->handleCoverUpload($_FILES["cover"], $id, $currentBook["cover"]);
            if ($coverUploadResult["success"]) {
                $coverName = $coverUploadResult["coverName"] ?? $currentBook["cover"];
                $updateSuccessful = true;
            } else {
                $message = $coverUploadResult["message"];
            }
        }

        // Comprobar si hay enlaces y convertir a JSON
        $links = isset($_POST["links"]) && !empty($_POST["links"]) ? json_encode($_POST["links"]) : null;

        $bookUpdated = (new Books())->updateBook(
            $id,
            htmlspecialchars($_POST["title"]),
            htmlspecialchars($_POST["author"]),
            htmlspecialchars($_POST["genre"]),
            htmlspecialchars($_POST["publisher"]),
            htmlspecialchars($_POST["pages"]),
            htmlspecialchars($_POST["year"]),
            $coverName,
            $links,
            htmlspecialchars($_POST["language"]),
            htmlspecialchars($_POST["description"]),
            htmlspecialchars($_POST["isbn"])
        );

        // Determinar si la actualización fue exitosa
        $updateSuccessful = $updateSuccessful || $bookUpdated;
        $message = $updateSuccessful ? "Libro actualizado correctamente" : $message;

        // Respuesta JSON
        header("Content-Type: application/json");
        echo json_encode(["success" => $updateSuccessful, "message" => $message]);
    }

    // ------------
    // Borrar libro
    // ------------
    public function deleteBook($id)
    {
        $success = (new Books())->deleteBook($id);
        echo json_encode(["success" => $success]);
    }
}
