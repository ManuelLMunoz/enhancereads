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
  <title>Gestionar libros</title>
  <link rel="stylesheet" href="assets/css/forms.css">
  <script src="assets/js/books.js" type="module"></script>
  <script src="assets/js/manageBooks.js" type="module"></script>

  <!-- Yearpicker para seleccionar el año en formato calendario (Requiere JQuery) -->
  <link rel="stylesheet" href="assets/css/yearpicker.css">
  <script src="assets/js/yearpicker.js" type="module"></script>
  <script>
    $(document).ready(function() {
      $("#year").yearpicker({
        startYear: 1900,
        endYear: new Date().getFullYear(),
        year: $("#year").val() || new Date().getFullYear()
      });
    });
  </script>

</head>

<body>

  <section class="section" id="main">

    <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logotipo de la marca"></a></h1><br>

    <div class="form">

      <h1 id="form-title"> Agregar <br>
        <span class="form-option" data-form="add-book">Libro</span> |
        <span class="form-option" data-form="add-author">Autor</span> |
        <span class="form-option" data-form="add-genre">Género</span> |
        <span class="form-option" data-form="add-publisher">Editorial</span>
      </h1>

      <!-- ---------------------------------------------------------------------------------- -->
      <!-- Formulario para agregar libros (En caso de fallo se mantienen los datos indicados) -->
      <!-- ---------------------------------------------------------------------------------- -->
      <form id="add-book" data-form="add-book" action="/add-book" method="POST" enctype="multipart/form-data">

        <label for="title"> Título
          <i class="fas fa-book icon"></i>
          <input id="title" type="text" name="title" required aria-label="Título" value="<?php echo isset($formData["title"]) ? htmlspecialchars($formData["title"]) : ""; ?>" />
        </label>

        <label for="author"> Autor
          <i class="fas fa-user icon"></i>
          <select id="author" name="author" required aria-label="Autor" data-selected="<?php echo isset($formData["author"]) ? $formData["author"] : "null"; ?>">
            <option value="" disabled <?php echo !isset($formData["author"]) ? "selected" : ""; ?>>Seleccione una opción</option>
            <!-- Obtenidos de forma dinámica de la BBDD -->
          </select>
        </label>

        <label for="genre"> Género
          <i class="fas fa-list-alt icon"></i>
          <select id="genre" name="genre" required aria-label="Género" data-selected="<?php echo isset($formData["genre"]) ? $formData["genre"] : "null"; ?>">
            <option value="" disabled <?php echo !isset($formData["genre"]) ? "selected" : ""; ?>>Seleccione una opción</option>
            <!-- Obtenidos de forma dinámica de la BBDD -->
          </select>
        </label>

        <label for="publisher"> Editorial
          <i class="fas fa-book-open icon"></i>
          <select id="publisher" name="publisher" required aria-label="Editorial" data-selected="<?php echo isset($formData["publisher"]) ? $formData["publisher"] : "null"; ?>">
            <option value="" disabled <?php echo !isset($formData["publisher"]) ? "selected" : ""; ?>>Seleccione una opción</option>
            <!-- Obtenidos de forma dinámica de la BBDD -->
          </select>
        </label>

        <label for="pages"> Páginas
          <i class="fa-solid fa-book-open icon"></i>
          <input id="pages" type="number" name="pages" required aria-label="Páginas" value="<?php echo isset($formData["pages"]) ? htmlspecialchars($formData["pages"]) : ""; ?>" />
        </label>

        <label for="year"> Año
          <i class="fas fa-calendar-alt icon"></i>
          <input id="year" type="number" name="year" maxlength="4" required aria-label="Año" value="<?php echo isset($formData["year"]) ? htmlspecialchars($formData["year"]) : ""; ?>" />
        </label>

        <label for="language"> Idioma
          <i class="fas fa-language icon"></i>
          <select id="language" name="language" required aria-label="Idioma">
            <option value="Español" <?php echo (isset($formData["language"]) && $formData["language"] === "Español") ? "selected" : ""; ?>>Español</option>
            <option value="Inglés" <?php echo (isset($formData["language"]) && $formData["language"] === "Inglés") ? "selected" : ""; ?>>Inglés</option>
          </select>
        </label>

        <label for="isbn"> ISBN
          <i class="fas fa-barcode icon"></i>
          <input id="isbn" type="text" name="isbn" aria-label="isbn" value="<?php echo isset($formData["isbn"]) ? htmlspecialchars($formData["isbn"]) : ""; ?>" maxlength="13" />
        </label>

        <label for="cover"> Portada
          <i class="fas fa-image icon"></i>
          <input id="cover" type="file" name="cover" required aria-label="Portada" accept="image/*" />
        </label>

        <label for="description">Descripción
          <textarea id="description" name="description" aria-label="Descripción"><?php echo isset($formData["description"]) ? htmlspecialchars($formData["description"]) : ""; ?></textarea>
        </label>

        <label for="links">Enlaces</label>
        <div id="links-container">
          <?php
          $links = !empty($formData["links"]) ? $formData["links"] : [""];
          foreach ($links as $link): ?>
            <div class="link-input">
              <div class="remove-link-icon-container">
                <i class="fas fa-trash-alt remove-link-icon"></i>
              </div>
              <input type="url" name="links[]" required placeholder="Ingresa un enlace" value="<?php echo htmlspecialchars($link); ?>">
            </div>
          <?php endforeach; ?>
        </div>
        <a href="#" id="add-link">Agregar enlace</a>

        <button type="submit" style="margin-top: 15px;">Agregar</button>
        <button type="button" onclick="window.location.href='/books'">Regresar</button>

      </form>

      <!-- ------------------------------- -->
      <!-- Formulario para agregar autores -->
      <!-- ------------------------------- -->
      <form id="add-author" data-form="add-author" action="/add-author" method="POST">

        <label for="new-author"> Nombre del autor
          <i class="fas fa-user icon"></i>
          <input type="text" id="new-author" name="new_author" required aria-label="Nombre del autor" />
        </label>

        <button type="submit">Agregar</button>
        <button type="button" onclick="window.location.href='/books'">Regresar</button>

      </form>

      <!-- ------------------------------- -->
      <!-- Formulario para agregar géneros -->
      <!-- ------------------------------- -->
      <form id="add-genre" data-form="add-genre" action="/add-genre" method="POST">

        <label for="new-genre"> Nombre del género
          <i class="fas fa-list-alt icon"></i>
          <input type="text" id="new-genre" name="new_genre" required aria-label="Nombre del género" />
        </label>

        <button type="submit">Agregar</button>
        <button type="button" onclick="window.location.href='/books'">Regresar</button>

      </form>

      <!-- ----------------------------------- -->
      <!-- Formulario para agregar editoriales -->
      <!-- ----------------------------------- -->
      <form id="add-publisher" data-form="add-publisher" action="/add-publisher" method="POST">

        <label for="new-publisher"> Nombre de la editorial
          <i class="fas fa-book-open icon"></i>
          <input type="text" id="new-publisher" name="new_publisher" required aria-label="Nombre de la editorial" />
        </label>

        <button type="submit">Agregar</button>
        <button type="button" onclick="window.location.href='/books'">Regresar</button>

      </form>

    </div>

  </section>

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

</body>

</html>