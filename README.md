
# Enhancereads

**Enhancereads** es una aplicación web enfocada en el **desarrollo personal**, siendo nuestra principal misión ser el punto de encuentro de todos aquellos interesados en el ámbito.

Os ofrecemos un **amplio catálogo de libros** sobre finanzas, habilidades sociales, salud mental, entre otros, que os guiarán para dar vuestra mejor versión.

También se incluye un apartado de posts donde podéis **valorar y aportar conocimiento** a la comunidad sobre diversos tópicos, experiencias y anécdotas.

## Características

- **Interfaz amigable**: Diseño intuitivo y muy fácil de usar.
- **Funcionalidad en PHP**: Se utiliza PHP para la lógica del servidor.
- **Gestión de dependencias**: Uso de Composer para manejar las dependencias PHP.
- **Frontend sin frameworks**: HTML, CSS y JavaScript puro para el frontend.

## Requisitos del sistema

- Servidor web con soporte para PHP 8.1 o superior.
- Composer para la gestión de dependencias.
- Navegador moderno (Chrome, Firefox, Safari, etc.)

## Instalación

1. **Clonar el repositorio:**

    ```bash
    git clone https://github.com/tuusuario/enhancereads.git
    ```

2. **Instalar dependencias con Composer:**

    ```bash
    composer install
    ```

3. **Configurar el servidor web:**

    - Configura tu servidor web para apuntar al directorio `public/` del proyecto.

4. **Ejecutar la aplicación:**

    - Abre tu navegador y accede a `http://localhost/`.

## Estructura del Proyecto

Este proyecto está organizado de la siguiente manera:

- **public/**: Contiene los archivos públicos accesibles, como `index.php`, imágenes, estilos CSS, JS y fuentes.
- **src/**: Contiene los archivos PHP de la aplicación. Dado que se sigue el patrón MVC, aquí se encuentran los archivos correspondientes a las vistas, modelos y controladores.
- **vendor/**: Directorio donde Composer instala las dependencias.
- **lib/**: Contiene bibliotecas adicionales necesarias para la aplicación.
- **sql/**: Directorio destinado a almacenar scripts SQL, como los utilizados para crear y poblar bases de datos.
- **.htaccess**: Archivo de configuración para el servidor web, utilizado para controlar la redirección y otros comportamientos del servidor.
- **composer.json**: Archivo de configuración de Composer, especificando las dependencias del proyecto.
- **composer.lock**: Archivo generado automáticamente por Composer que garantiza que las mismas versiones de dependencias se instalen en diferentes entornos.
- **README.md**: Archivo de documentación del proyecto.

## Contribuir

1. Haz un fork del proyecto.
2. Crea una nueva rama (`git checkout -b feature/nueva-funcionalidad`).
3. Realiza los cambios necesarios y haz commit (`git commit -am 'Añadir nueva funcionalidad'`).
4. Sube los cambios a tu repositorio (`git push origin feature/nueva-funcionalidad`).
5. Abre un Pull Request en GitHub.

## Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE.txt).

---


**Desarrollado por Manuel Luis Muñoz Sánchez**