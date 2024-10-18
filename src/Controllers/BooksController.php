<?php

namespace Src\Controllers;

use Src\Models\Books;

class BooksController extends Controller
{
    // Se retorna la vista de la lista de libros con soporte para paginación
    public function books($page = 1)
    {
        return $this->view("books", ["page" => $page]);
    }

    // Se retorna la vista de administración de libros
    public function manageBook()
    {
        return $this->view("manage-books");
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
            "language" => $_POST["language"] ?? [],
            "year" => $_POST["year"] ?? "",
            "pages" => $_POST["pages"] ?? ""
        ];

        $booksData = (new Books())->getBooks($params);
        $books = $booksData["books"];

        // Valores de la paginación
        $totalRows = $booksData["total"];
        $totalPages = ceil($totalRows / $params["limit"]);
        $page = max(min($params["page"], $totalPages), 1);
        $startElement = ($page - 1) * $params["limit"] + 1;
        $endElement = min($page * $params["limit"], $totalRows);

        // Se renderiza la vista de los libros
        ob_start();
        include(__DIR__ . "/../views/components/fetch_books.php");
        $output = ob_get_clean();

        // Respuesta en formato JSON
        echo json_encode([
            "html" => $output,
            "filters" => [
                "authors" => (new Books())->getAuthors(),
                "genres" => (new Books())->getGenres(),
                "languages" => (new Books())->getLanguages(),
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

    public function fetchAuthorsAndGenres()
    {
        echo json_encode([
            "success" => true,
            "authors" => (new Books())->getAllAuthors(),
            "genres" => (new Books())->getAllGenres()
        ]);
    }

    // ------------------------------
    // Subida de la imagen de portada
    // ------------------------------
    private function handleCoverUpload($cover, $bookId, $existingCoverName = null)
    {
        // Si no se sube un nuevo archivo, mantener el nombre de portada existente
        if ($cover["error"] === UPLOAD_ERR_NO_FILE) {
            return $existingCoverName;
        }

        // Verificar si hubo algún error durante la subida
        if ($cover["error"] !== UPLOAD_ERR_OK) {
            return ["success" => false, "message" => "Error al subir el archivo"];
        }

        // Comprobar que el tamaño del archivo no exceda de 1MB
        if ($cover["size"] > 1048576) {
            return ["success" => false, "message" => "El tamaño máximo de imagen permitido es 1MB"];
        }

        // Verificar que la extensión del archivo sea válida
        $extension = strtolower(pathinfo($cover["name"], PATHINFO_EXTENSION));
        if (!in_array($extension, ["jpg", "jpeg", "png", "webp"])) {
            return ["success" => false, "message" => "Solo se permiten archivos con extensión: jpg, jpeg, png y webp"];
        }

        // Generar el nuevo nombre de archivo y la ruta de destino
        $coverNewName = "cover_book_" . $bookId . ".webp";
        $destPath = __DIR__ . "/../../public/assets/img/books/" . $coverNewName;

        // Intentar mover el archivo y devolver el resultado
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

        // Insertar el libro sin la portada para obtener el ID
        $book = (new Books())->addBook(
            $formData["title"],
            $formData["author"],
            $formData["genre"],
            $formData["pages"],
            $formData["year"],
            null,
            $formData["link"],
            $formData["language"]
        );

        if (!$book) {
            return $this->view("manage-books", ["error" => "Error al agregar el libro", "formData" => $formData]);
        }

        $bookId = $book["id"];

        // Verificar si se ha enviado un archivo de portada
        if (!empty($_FILES["cover"]) && $_FILES["cover"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->handleCoverUpload($_FILES["cover"], $bookId);

            if (empty($uploadResult["success"])) {
                (new Books())->deleteBook($bookId);
                return $this->view("manage-books", ["error" => $uploadResult["message"] ?? "Error desconocido", "formData" => $formData]);
            }

            // Actualizar el libro con el nombre de la portada
            if (!(new Books())->updateBookCover($bookId, $uploadResult["coverName"] ?? null)) {
                return $this->view("manage-books", ["error" => "Libro agregado, pero hubo un error al actualizar la portada", "formData" => $formData]);
            }
        }

        return $this->view("manage-books", ["success" => "Libro agregado exitosamente"]);
    }

    public function addAuthor()
    {
        $author = (new Books())->addAuthor($_POST["new_author"]);
        return $this->view("manage-books", $author ? ["success" => "Autor agregado exitosamente"] : ["error" => "El autor ya existe"]);
    }

    public function addGenre()
    {
        $genre = (new Books())->addGenre($_POST["new_genre"]);
        return $this->view("manage-books",  $genre ? ["success" => "Género agregado exitosamente"] : ["error" => "El género ya existe"]);
    }

    // --------------------------
    // Editar y actualizar libros
    // --------------------------
    public function editBooks($id)
    {
        $book = (new Books())->getBookById($id);

        // Obtener todos los autores y géneros
        $authors = (new Books())->getAuthors();
        $genres = (new Books())->getGenres();

        if (!$book) {
            header("Location: /");
            exit();
        }

        return $this->view("edit-books", ["book" => $book, "authors" => $authors, "genres" => $genres]);
    }

    public function updateBook()
    {
        $id = $_POST["id"];
        $currentBook = (new Books())->getBookById($id);
        $coverName = $currentBook["cover"];
        $updateSuccessful = false;
        $message = "Error al actualizar el libro";  // Mensaje por defecto

        // Manejar la actualización de la portada si se ha enviado un nuevo archivo
        if (isset($_FILES["cover"]) && $_FILES["cover"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $coverUploadResult = $this->handleCoverUpload($_FILES["cover"], $id, $currentBook["cover"]);
            if ($coverUploadResult["success"]) {
                $coverName = $coverUploadResult["coverName"] ?? $currentBook["cover"];
                $updateSuccessful = true;
            } else {
                // Si hubo un error al subir la portada, capturar el mensaje
                $message = $coverUploadResult["message"];
            }
        }

        // Actualizar el libro
        $bookUpdated = (new Books())->updateBook(
            $id,
            $_POST["title"],
            $_POST["author"],
            $_POST["genre"],
            $_POST["pages"],
            $_POST["year"],
            $coverName,
            $_POST["link"],
            $_POST["language"]
        );

        $updateSuccessful = $updateSuccessful || $bookUpdated;

        // Si la actualización del libro fue exitosa, cambiar el mensaje
        if ($updateSuccessful) {
            $message = "Libro actualizado correctamente";
        }

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
