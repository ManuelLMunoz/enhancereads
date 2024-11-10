<?php
// Se inicia la sesión si no lo está 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Si el usuario no es "admin", se redirige a la página de inicio
if ($_SESSION["role"] !== "admin") {
  header("Location:/");
  exit();
}

// Se obtiene el nombre del formulario
$activeForm = $_POST["form_name"] ?? null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Configuración base -->
  <?php require_once("components/head.php"); ?>

  <!-- Configuración concreta -->
  <title>Gestionar libros</title>
  <link rel="stylesheet" href="assets/css/forms.css">
  <script src="assets/js/books.js" type="module"></script>
  <script src="assets/js/manageBooks.js" type="module"></script>
  <script>
    // Pasar el valor de $activeForm a JS
    const activeForm = "' . htmlspecialchars($activeForm) . '";
  </script>

  <!-- Yearpicker para seleccionar el año en formato calendario (Requiere JQuery) -->
  <link rel="stylesheet" href="assets/css/yearpicker.css">
  <script src="assets/js/yearpicker.js" type="module"></script>
  <script>
    $(document).ready(function() {
      // Obtener el valor actual del campo "year"
      var selectedYear = $("#year").val();

      // Inicializar el yearpicker con el valor actual si existe, sino usar el año actual
      $("#year").yearpicker({
        startYear: 1900,
        endYear: new Date().getFullYear(),
        year: selectedYear ? selectedYear : new Date().getFullYear()
      });

      // Si ya hay un valor en el campo "year", establecerlo después de inicializar el yearpicker
      if (selectedYear) {
        $("#year").val(selectedYear);
      }
    });
  </script>

</head>

<body>

  <!-- Sección principal -->
  <section class="section" id="main">

    <!-- Logo -->
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
      <form id="add-book" data-form="add-book" class="<?php echo $activeForm === "add-book" ? "" : "hidden"; ?>" action="/add-book" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="form_name" value="add-book">

        <!-- Título -->
        <label for="title"> Título
          <i class="fas fa-book icon"></i>
          <input id="title" type="text" name="title" required aria-label="Título" value="<?php echo isset($formData["title"]) ? htmlspecialchars($formData["title"]) : ""; ?>" />
        </label>

        <!-- Autor -->
        <label for="author"> Autor
          <i class="fas fa-user icon"></i>
          <select id="author" name="author" required aria-label="Autor" data-selected="<?php echo isset($formData["author"]) ? $formData["author"] : "null"; ?>">
            <option value="" disabled <?php echo !isset($formData["author"]) ? "selected" : ""; ?>>Seleccione una opción</option>
            <!-- Obtenidos de forma dinámica de la BBDD -->
          </select>
        </label>

        <!-- Género -->
        <label for="genre"> Género
          <i class="fas fa-list-alt icon"></i>
          <select id="genre" name="genre" required aria-label="Género" data-selected="<?php echo isset($formData["genre"]) ? $formData["genre"] : "null"; ?>">
            <option value="" disabled <?php echo !isset($formData["genre"]) ? "selected" : ""; ?>>Seleccione una opción</option>
            <!-- Obtenidos de forma dinámica de la BBDD -->
          </select>
        </label>

        <!-- Editorial -->
        <label for="publisher"> Editorial
          <i class="fas fa-book-open icon"></i>
          <select id="publisher" name="publisher" required aria-label="Editorial" data-selected="<?php echo isset($formData["publisher"]) ? $formData["publisher"] : "null"; ?>">
            <option value="" disabled <?php echo !isset($formData["publisher"]) ? "selected" : ""; ?>>Seleccione una opción</option>
            <!-- Obtenidos de forma dinámica de la BBDD -->
          </select>
        </label>

        <!-- Páginas -->
        <label for="pages"> Páginas
          <i class="fa-solid fa-book-open icon"></i>
          <input id="pages" type="number" name="pages" required aria-label="Páginas" value="<?php echo isset($formData["pages"]) ? htmlspecialchars($formData["pages"]) : ""; ?>" />
        </label>

        <!-- Año -->
        <label for="year"> Año
          <i class="fas fa-calendar-alt icon"></i>
          <input id="year" type="number" name="year" maxlength="4" required aria-label="Año" value="<?php echo isset($formData["year"]) ? htmlspecialchars($formData["year"]) : ""; ?>" />
        </label>

        <!-- Idioma -->
        <label for="language"> Idioma
          <i class="fas fa-language icon"></i>
          <select id="language" name="language" required aria-label="Idioma">
            <option value="Español" <?php echo (isset($formData["language"]) && $formData["language"] === "Español") ? "selected" : ""; ?>>Español</option>
            <option value="Inglés" <?php echo (isset($formData["language"]) && $formData["language"] === "Inglés") ? "selected" : ""; ?>>Inglés</option>
          </select>
        </label>

        <!-- ISBN -->
        <label for="isbn"> ISBN
          <i class="fas fa-barcode icon"></i>
          <input id="isbn" type="text" name="isbn" required aria-label="isbn" value="<?php echo isset($formData["isbn"]) ? htmlspecialchars($formData["isbn"]) : ""; ?>" maxlength="13" />
        </label>

        <!-- Portada -->
        <label for="cover"> Portada
          <i class="fas fa-image icon"></i>
          <input id="cover" type="file" name="cover" required aria-label="Portada" accept="image/*" />
        </label>

        <!-- Descripción -->
        <label for="description">Descripción
          <textarea id="description" name="description" required aria-label="Descripción"><?php echo isset($formData['description']) ? htmlspecialchars($formData['description']) : ''; ?></textarea>
        </label>

        <!-- Enlaces -->
        <label for="links">Enlaces</label>
        <div id="links-container">
          <div class="link-input">
            <div class="remove-link-icon-container">
              <i class="fas fa-trash-alt remove-link-icon"></i>
            </div>
            <input type="url" name="links[]" required placeholder="Ingresa un enlace">
          </div>
        </div>
        <a href="#" id="add-link">Agregar enlace</a>

        <button type="submit" style="margin-top: 15px;">Agregar</button>
        <button type="button" onclick="window.location.href='/books'">Regresar</button>

      </form>

      <!-- ------------------------------- -->
      <!-- Formulario para agregar autores -->
      <!-- ------------------------------- -->
      <form id="add-author" data-form="add-author" class="<?php echo $activeForm === "add-author" ? "" : "hidden"; ?>" action="/add-author" method="POST">
        <input type="hidden" name="form_name" value="add-author">

        <!-- Autor -->
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
      <form id="add-genre" data-form="add-genre" class="<?php echo $activeForm === "add-genre" ? "" : "hidden"; ?>" action="/add-genre" method="POST">
        <input type="hidden" name="form_name" value="add-genre">

        <!-- Género -->
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
      <form id="add-publisher" data-form="add-publisher" class="<?php echo $activeForm === "add-publisher" ? "" : "hidden"; ?>" action="/add-publisher" method="POST">
        <input type="hidden" name="form_name" value="add-publisher">

        <!-- Editorial -->
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