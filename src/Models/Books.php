<?php

namespace Src\Models;

use PDO;
use PDOException;

class Books extends Connection
{
    // -------------------------
    // Obtención de los libros
    // -------------------------
    public function getBooks($params)
    {
        try {
            // Preparar los parámetros básicos
            $search = "%" . $params["search"] . "%";
            $limit = $params["limit"];
            $offset = ($params["page"] - 1) * $limit;
            $order = $params["order"];

            // Configuración de los filtros
            $filters = [
                "author" => ["field" => "a.id", "values" => $params["author"] ?? []],
                "genre" => ["field" => "g.id", "values" => $params["genre"] ?? []],
                "publisher" => ["field" => "p.id", "values" => $params["publisher"] ?? []],
                "language" => ["field" => "b.language", "values" => $params["language"] ?? []],
                "pages" => ["field" => "b.pages", "values" => $params["pages"] ?? null]
            ];

            // Construcción de las cláusulas y valores de filtro
            $filterClauses = [];
            $filterValues = [];
            foreach ($filters as $filter) {
                if (!empty($filter["values"])) {
                    // Usar "IN" para múltiples valores, o ">" / "=" para filtros de valores únicos
                    $placeholders = is_array($filter["values"]) ? implode(",", array_fill(0, count($filter["values"]), "?")) : "?";
                    $operator = is_array($filter["values"]) ? "IN" : ($filter["values"] > 0 ? ">" : "=");
                    // Agregar la cláusula y los valores de filtro
                    $filterClauses[] = "{$filter["field"]} $operator ($placeholders)";
                    $filterValues = array_merge($filterValues, is_array($filter["values"]) ? $filter["values"] : [$filter["values"] > 0 ? $filter["values"] - 1 : $filter["values"]]);
                }
            }
            // Combinar las cláusulas con "AND" para la consulta final
            $filterQuery = $filterClauses ? "AND " . implode(" AND ", $filterClauses) : "";

            $baseQuery = "FROM books b LEFT JOIN authors a ON b.author_id = a.id LEFT JOIN genres g ON b.genre_id = g.id
            LEFT JOIN publishers p ON b.publisher_id = p.id  WHERE (b.title LIKE ? OR a.name LIKE ?) $filterQuery";

            $booksQuery = "SELECT b.id, b.title, b.pages, b.year, b.cover, b.link, b.language, a.name as author_name,
            g.name as genre_name, p.name as publisher_name $baseQuery ORDER BY b.title $order LIMIT ? OFFSET ?";

            $stmt = $this->connection->prepare($booksQuery);
            $params = array_merge([$search, $search], $filterValues, [$limit, $offset]);
            foreach ($params as $index => $value) {
                $stmt->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $stmt->execute();
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Consulta para obtener el conteo total de libros
            $totalQuery = "SELECT COUNT(*) as total $baseQuery";
            $totalStmt = $this->connection->prepare($totalQuery);
            $totalParams = array_merge([$search, $search], $filterValues);
            foreach ($totalParams as $index => $value) {
                $totalStmt->bindValue($index + 1, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
            $totalStmt->execute();
            $total = $totalStmt->fetch(PDO::FETCH_ASSOC)["total"];

            // Devolver los libros y el conteo total
            return ["books" => $books, "total" => $total];
        } catch (PDOException $e) {
            error_log("Error al mostrar los libros: " . $e->getMessage());
            return ["books" => [], "total" => 0];
        }
    }

    // ---------
    // Búsquedas
    // ---------

    public function getAuthors()
    {
        $query = "SELECT DISTINCT a.id, a.name FROM authors a JOIN books b ON a.id = b.author_id ORDER BY a.name";
        return $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllAuthors()
    {
        $query = "SELECT DISTINCT id, name FROM authors ORDER BY name";
        return $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGenres()
    {
        $query = "SELECT DISTINCT g.id, g.name FROM genres g JOIN books b ON g.id = b.genre_id ORDER BY g.name";
        return $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllGenres()
    {
        $query = "SELECT DISTINCT id, name FROM genres ORDER BY name";
        return $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublishers()
    {
        $query = "SELECT DISTINCT p.id, p.name FROM publishers p JOIN books b ON p.id = b.publisher_id ORDER BY p.name";
        return $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPublishers()
    {
        $query = "SELECT DISTINCT id, name FROM publishers ORDER BY name";
        return $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLanguages()
    {
        $query = "SELECT DISTINCT language FROM books";
        return $this->connection->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // -------------
    // Agregar datos
    // -------------
    public function addRecord($table, $name)
    {
        try {
            $stmt = $this->connection->prepare("SELECT id FROM $table WHERE name = :name");
            $stmt->execute(["name" => $name]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $stmt = $this->connection->prepare("INSERT INTO $table (name) VALUES (:name)");
                return $stmt->execute(["name" => $name]);
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al agregar registro en $table: " . $e->getMessage());
            return false;
        }
    }

    public function addAuthor($name)
    {
        return $this->addRecord("authors", $name);
    }

    public function addGenre($name)
    {
        return $this->addRecord("genres", $name);
    }

    public function addPublisher($name)
    {
        return $this->addRecord("publishers", $name);
    }


    public function addBook($title, $authorId, $genreId, $publisherId, $pages, $year, $cover, $links, $language, $description, $isbn)
    {
        $link = json_encode($links);

        $query = "INSERT INTO books (title, author_id, genre_id, publisher_id, pages, year, cover, link, language, description, isbn)
                  VALUES (:title, :authorId, :genreId, :publisherId, :pages, :year, :cover, :link, :language, :description, :isbn)";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute(compact("title", "authorId", "genreId", "publisherId", "pages", "year", "cover", "link", "language", "description", "isbn"));
            return ["id" => $this->connection->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Error al agregar libro: " . $e->getMessage());
            return false;
        }
    }

    // -----------------
    // Actualizar libros
    // -----------------
    public function getBookById($id)
    {
        $query = "SELECT b.*, a.name AS author_name, g.name AS genre_name, p.name AS publisher_name FROM books b LEFT JOIN authors a ON b.author_id = a.id
        LEFT JOIN genres g ON b.genre_id = g.id LEFT JOIN publishers p ON b.publisher_id = p.id WHERE b.id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateBook($id, $title, $author, $genre, $publisher, $pages, $year, $cover, $link, $language, $description, $isbn)
    {
        try {
            $query = "UPDATE books SET title = :title, author_id = :author, genre_id = :genre, publisher_id = :publisher, pages = :pages, year = :year,
            cover = :cover, link = :link, language = :language, description = :description, isbn = :isbn WHERE id = :id";
            $stmt = $this->connection->prepare($query);
            $stmt->execute(compact("title", "author", "genre", "publisher", "pages", "year", "cover", "link", "language", "description", "isbn", "id"));
            return $stmt->rowCount() > 0; // Devuelve true si se actualizaron filas
        } catch (PDOException $e) {
            error_log("Error al actualizar el libro: " . $e->getMessage());
            return false;
        }
    }

    public function updateBookCover($bookId, $coverName)
    {
        $query = "UPDATE books SET cover = :cover WHERE id = :id";
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute(["cover" => $coverName, "id" => $bookId]);
            return true;
        } catch (PDOException $e) {
            error_log("Error al actualizar la portada del libro: " . $e->getMessage());
            return false;
        }
    }

    // -------------
    // Borrar libros
    // -------------
    public function deleteBook($id)
    {
        try {
            // Obtener y borrar la portada del libro si existe
            $stmt = $this->connection->prepare("SELECT cover FROM books WHERE id = :id");
            $stmt->execute(compact("id"));
            $cover = $stmt->fetchColumn();

            if ($cover && $cover !== "document.webp" && file_exists($coverPath = __DIR__ . "/../../public/assets/img/books/" . $cover)) {
                unlink($coverPath);
            }

            $stmt = $this->connection->prepare("DELETE FROM books WHERE id = :id");
            return $stmt->execute(compact("id"));
        } catch (PDOException $e) {
            error_log("Error al eliminar libro: " . $e->getMessage());
            return false;
        }
    }
}
