<!-- Mostrar todos los libros encontrados en la BBDD -->
<?php if (!empty($books)) : ?>
    <div class="book-container">
        <?php foreach ($books as $book) : ?>

            <div class="book-info">

                <a href="books/<?php echo $book["id"]; ?>/<?php echo $this->sanitizeTitle($book["title"]); ?>">
                    <img src="assets/img/books/<?php echo $book["cover"]; ?>" alt="Portada del libro">
                </a>

                <p id="title"><?php echo $book["title"]; ?></p>
                <p><strong>Autor:</strong> <?php echo $book["author_name"]; ?></p>
                <p><strong>Género:</strong> <?php echo $book["genre_name"]; ?></p>
                <p><?php echo !empty($book["publisher_name"]) ? "<strong>Editorial:</strong> " . $book["publisher_name"] : ""; ?></p>
                <p><strong>Idioma:</strong> <?php echo $book["language"]; ?></p>
            </div>

        <?php endforeach; ?>
    </div>

    <?php require_once(__DIR__ . "/../components/pagination.php"); ?>

<?php else : ?>
    <p>No se encontraron libros 😥</p>
<?php endif; ?>