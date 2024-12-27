<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION["role"] !== "admin") {
    header("Location:/");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once(__DIR__ . "/../components/head.php"); ?>
    <title>Editar - <?php echo htmlspecialchars($book["title"]); ?></title>
    <link rel="stylesheet" href="assets/css/forms.css">
    <script src="assets/js/books.js" type="module"></script>

    <!-- Yearpicker para seleccionar el año en formato calendario (Requiere JQuery) -->
    <link rel="stylesheet" href="assets/css/yearpicker.css">
    <script src="assets/js/yearpicker.js" type="module"></script>
    <script>
        $(document).ready(function() {
            // Inicializar el yearpicker con el año del libro
            const year = <?php echo json_encode($book["year"]); ?>;
            $("#year").val(year);
            $("#year").yearpicker({
                startYear: 1900,
                endYear: new Date().getFullYear(),
                year: year
            });
        });
    </script>

</head>

<body>

    <section id="main">

        <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1><br>

        <div class="form">

            <h1>Editar Libros</h1>

            <!-- ----------------------------- -->
            <!-- Formulario para editar libros -->
            <!-- ----------------------------- -->
            <form id="edit-books" action="/update-book" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?php echo htmlspecialchars($book["id"]); ?>">

                <label for="title"> Título
                    <i class="fas fa-book icon"></i>
                    <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($book["title"]); ?>" required aria-label="Título" />
                </label>

                <label for="author"> Autor
                    <i class="fas fa-user icon"></i>
                    <select id="author" name="author" required aria-label="Autor" data-selected="<?php echo isset($book["author_id"]) ? $book["author_id"] : "null"; ?>">
                        <option value="" disabled selected>Seleccione una opción</option> <!-- Obtenidos de forma dinámica de la BBDD -->
                    </select>
                </label>

                <label for="genre"> Género
                    <i class="fas fa-list-alt icon"></i>
                    <select id="genre" name="genre" required aria-label="Género" data-selected="<?php echo isset($book["genre_id"]) ? $book["genre_id"] : "null"; ?>">
                        <option value="" disabled selected>Seleccione una opción</option> <!-- Obtenidos de forma dinámica de la BBDD -->
                    </select>
                </label>

                <label for="publisher"> Editorial
                    <i class="fas fa-book-open icon"></i>
                    <select id="publisher" name="publisher" aria-label="Editorial" data-selected="<?php echo isset($book["publisher_id"]) ? $book["publisher_id"] : "null"; ?>">
                        <option value="" disabled selected>Seleccione una opción</option> <!-- Obtenidos de forma dinámica de la BBDD -->
                    </select>
                </label>

                <label for="pages"> Páginas
                    <i class="fa-solid fa-book-open icon"></i>
                    <input id="pages" type="number" name="pages" value="<?php echo htmlspecialchars($book["pages"]); ?>" aria-label="Páginas" />
                </label>

                <label for="year"> Año
                    <i class="fas fa-calendar-alt icon"></i>
                    <input id="year" type="text" name="year" maxlength="4" value="<?php echo htmlspecialchars($book["year"]); ?>" aria-label="Año" />
                </label>

                <label for="language"> Idioma
                    <i class="fas fa-language icon"></i>
                    <select id="language" name="language" required aria-label="Idioma">
                        <option value="Español" <?php echo (htmlspecialchars($book["language"]) == "Español") ? "selected" : ""; ?>>Español</option>
                        <option value="Inglés" <?php echo (htmlspecialchars($book["language"]) == "Inglés") ? "selected" : ""; ?>>Inglés</option>
                    </select>
                </label>

                <label for="isbn"> ISBN
                    <i class="fas fa-barcode icon"></i>
                    <input id="isbn" type="text" name="isbn" aria-label="ISBN" value="<?php echo htmlspecialchars($book["isbn"]); ?>" maxlength="13" />
                </label>

                <label for="cover"> Portada
                    <i class="fas fa-image icon"></i>
                    <input id="cover" type="file" name="cover" aria-label="Foto" accept="image/*" />
                </label>

                <label for="description"> Descripción
                    <textarea id="description" name="description" aria-label="Descripción"><?php echo htmlspecialchars($book["description"]); ?></textarea>
                </label>

                <label for="links"> Enlaces </label>
                <div id="links-container">
                    <?php
                    $links = json_decode($book["link"], true) ?: [];
                    // Generar un input por cada enlace
                    foreach ($links as $index => $link): ?>
                        <div class="link-input">
                            <div class="remove-link-icon-container">
                                <i class="fas fa-trash-alt remove-link-icon"></i>
                            </div>
                            <input type="url" name="links[]" id="link_<?= $index ?>" value="<?= htmlspecialchars($link) ?>" aria-label="Enlaces" />
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="#" id="add-link">Agregar enlace</a>

                <!-- Mostrar mensajes toast al usuario según el tipo indicado en el controlador -->
                <script>
                    <?php
                    $types = ["error", "success", "warning", "info"];
                    foreach ($types as $type) {
                        if (!empty($$type)) {
                            echo "showToast(" . json_encode($$type) . ", \"$type\");";
                            break;
                        }
                    }
                    ?>
                </script>

                <button type="submit" style="margin-top: 15px;">Actualizar </button>
                <button type="button" onclick="window.history.back()">Regresar</button>

            </form>
        </div>

    </section>

</body>

</html>