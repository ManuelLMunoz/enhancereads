
# 📚 Enhancereads

**Enhancereads** es una aplicación web enfocada en el **desarrollo personal**, siendo nuestra principal misión ser el punto de encuentro de todos aquellos interesados en el ámbito.

Os ofrecemos un **amplio catálogo de libros** sobre finanzas, habilidades sociales, salud mental, entre otros, que os guiarán para dar vuestra mejor versión.

También se incluye un apartado de posts donde podéis **valorar y aportar conocimiento** a la comunidad sobre diversos tópicos, experiencias y anécdotas.

## 🚀 Características principales

- **📖 Amplio catálogo de libros:** Encuentra los títulos más relevantes del desarrollo personal.
- **👥 Comunidad participativa:** Valora y comparte ideas, experiencias y aprendizajes con otros usuarios.
- **💡 Interfaz intuitiva:** Diseño moderno, minimalista, fácil de usar y agradable a la vista.
- **🔧 Tecnología sólida:** 
  - Backend desarrollado en **PHP** para una lógica robusta.
  - Gestión de dependencias mediante **Composer**.
  - **Frontend sin frameworks**, usando HTML, CSS y JavaScript puro para mayor control y personalización.


## 🖥️ Requisitos del sistema

Antes de instalar **Enhancereads**, asegúrate de cumplir con los siguientes requisitos:

- **Servidor web** con soporte para **PHP 8.1** o superior.
- **Composer** instalado para la gestión de dependencias.
- Un **navegador moderno** (Chrome, Firefox, Safari, Edge, etc.).

## ⚙️ Instalación

Sigue estos pasos para instalar y ejecutar **Enhancereads** en tu sistema:

1. **Clonar el repositorio de GitHub:**

    ```bash
    git clone https://github.com/ManuelLMunoz/enhancereads.git
    ```

2. **Configurar el servidor web:**
-  Ejecuta el script `web.sh`, diseñado para distribuciones GNU/Linux basadas en **Debian/Ubuntu**. Si usas otras distribuciones como **CentOS/Fedora**, es posible que necesites adaptar algunos comandos.

    ```bash
    sudo bash enhancereads/linux/web.sh
    ```

3. **Lanzar la aplicación:**

    -  Una vez configurado el servidor, abre tu navegador y accede a: `https://www.enhancereads.com/`.

## 📂 Estructura del proyecto

La organización del proyecto sigue una estructura clara y modular:

- **`public/`**: Archivos accesibles públicamente (imágenes, CSS, JavaScript, etc.).
- **`src/`**: Contiene la lógica del servidor, organizada según el patrón **MVC** (Modelos, Vistas y Controladores).
- **`vendor/`**: Carpeta generada automáticamente por **Composer**, donde se instalan las dependencias.
- **`sql/`**: Scripts SQL para la creación y configuración de la base de datos.
- **`.htaccess`**: Configuración personalizada del servidor para redirecciones y seguridad.
- **`composer.json`** y **`composer.lock`**: Archivos de configuración de dependencias de Composer.
- **`README.md`**: Documentación detallada del proyecto.

## 📜 Licencia

Este proyecto está licenciado bajo la **[Licencia MIT](LICENSE.txt).

## 🌟 Contribuciones

¡Todos los aportes son bienvenidos! Si tienes ideas, mejoras o encuentras algún problema, no dudes en abrir un [issue](https://github.com/ManuelLMunoz/enhancereads/issues) o enviar un pull request.


### 👨‍💻 Autor

**Desarrollado por:**  
**Manuel Luis Muñoz Sánchez**  
[GitHub](https://github.com/ManuelLMunoz)