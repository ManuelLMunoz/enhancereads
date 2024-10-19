
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

1. **Clonar el repositorio de GitHub:**

    ```bash
    git clone https://github.com/ManuelLMunoz/enhancereads.git
    ```

2. **Ejecutar el script de bash "web.sh" para configurar el servidor web:**
- Dicho script está adaptado a distribuciones GNU/Linux basadas en Debian / Ubuntu (En distros como CentOS o Fedora, algunos de los comandos y configuraciones pueden no ser compatibles y necesitarán ser adaptados)

    ```bash
    . /enhancereads/linux/web.sh
    ```

3. **Ejecutar la aplicación:**

    - Abre tu navegador y accede a `http://enhancereads.com/`.

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

## Licencia

Este proyecto está licenciado bajo la [MIT License](LICENSE.txt).

---

**Desarrollado por Manuel Luis Muñoz Sánchez**