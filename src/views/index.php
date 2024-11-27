<!DOCTYPE html>
<html lang="es">

<head>
  <?php require_once("components/head.php"); ?>
  <title>Enhancereads</title>
  <link rel="stylesheet" href="assets/css/index.css">
  <script src="assets/js/index.js" type="module"></script>
</head>

<body>

  <section id="main">

    <?php require_once("components/navbar.php"); ?>

    <div class="brand">
      <h1><a href="."><img id="logotype" src="assets/img/logo.webp" alt="Logo de la marca"></a></h1>
      <h2><img id="slogan" src="assets/img/slogan.webp" alt="Slogan de la marca"></h2>
    </div>

  </section>

  <section id="options">

    <a href="books/" class="books">
      <h1>Libros</h1>
    </a>

    <a href="posts/" class="posts">
      <h1>Posts</h1>
    </a>

  </section>

  <section id="about-us">

    <div class="know-us">
      <h1>Conócenos</h1>

      <div class="text">
        <p> Apasionado por el desarrollo personal, mi principal misión con este página es ser el <strong> punto de encuentro </strong> para todos aquellos interesados en este ámbito.</p>
        <p> Se ponen a disposición un <strong> amplio catálogo de libros </strong> sobre finanzas, habilidades sociales, salud mental, entre otros, que os guiarán para dar vuestra mejor versión.</p>
        <p> También se incluye un apartado de posts donde podéis <strong> obtener y aportar conocimiento </strong> a la comunidad sobre diversos tópicos, experiencias y anécdotas.</p>
      </div>

    </div>

    <div class="testimonials">
      <h1>Valoraciones de los usuarios</h1>

      <div class="testimonial-slider">
        <div class="slides">

          <div class="slide">
            <p class="testimonial">"Hay gran variedad de libros sobre muchos tópicos, y la sección de posts es de alta calidad, ya que toca temas variados como las duchas frías y la salud mental.
              Ideal para inspirarte y mejorar en tu vida diaria. ¡Muy recomendada!"</p>
            <p class="user">Alekai</p>
            <p class="rating" data-rating="5"></p>
          </div>

          <div class="slide">
            <p class="testimonial">"Enhancereads ha transformado mi vida por completo.
              Revisando el apartado de libros, encontré “Can't Hurt Me” de David Goggins. Sin pensarlo, lo compré y me ha abierto los ojos.
              ¡Ojalá no sea la única en aprovechar este potencial!"</p>
            <p class="user">Pilar Sousa</p>
            <p class="rating" data-rating="4.5"></p>
          </div>

          <div class="slide">
            <p class="testimonial">"Esta web es una joya para el desarrollo personal.
              Los libros y posts son excelentes, con consejos prácticos y experiencias enriquecedoras.<br>
              ¡Perfecta para quienes buscan mejorar su vida de manera integral!"</p>
            <p class="user">Miguel Ortiz</p>
            <p class="rating" data-rating="5"></p>
          </div>

        </div>
      </div>

    </div>

  </section>

  <?php require_once("components/footer.php"); ?>

</body>

</html>