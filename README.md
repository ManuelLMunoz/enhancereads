# 📚 Enhancereads

**Enhancereads** es una plataforma web dedicada al **desarrollo personal**, cuya misión principal es convertirse en el punto de referencia para todas las personas interesadas en esta área.

## 🚀 Características principales

- **📖 Extenso catálogo de recursos:** Explora libros, artículos y herramientas clave en desarrollo personal.
- **👥 Comunidad interactiva:** Participa en foros donde puedes compartir ideas, experiencias y aprendizajes con otros usuarios.
- **💡 Diseño intuitivo:** Interfaz minimalista, moderna y fácil de usar, adaptada para cualquier dispositivo.
- **🔐 Autenticación flexible:** Regístrate con tu correo electrónico o accede fácilmente mediante Google.


## ⚙️ Instalación

Sigue estos pasos para configurar y ejecutar la plataforma:

1. **Clonar el repositorio desde GitHub:**

    ```bash
    git clone https://github.com/ManuelLMunoz/enhancereads.git
    ```

2. **Configurar el servidor web:**  
   Ejecuta el script `web.sh`, diseñado para distribuciones GNU/Linux basadas en **Debian/Ubuntu**. Para otras distribuciones, como **CentOS/Fedora**, podría ser necesario ajustar algunos comandos.

    ```bash
    sudo bash enhancereads/linux/web.sh
    ```

3. **Accede a la aplicación:**  
   Una vez configurado el servidor, abrir un navegador y acceder a: `https://www.enhancereads.com/`.

## 📂 Estructura del proyecto

El código está organizado para garantizar su mantenimiento y escalabilidad:

```
/
|-- linux/ (Scripts para configurar los servidores Linux)
|-- public/
|   |-- assets/ (CSS,tipografías, imágenes, JavaScript)
|   |-- index.php (Punto de entrada principal)
|   |-- .htaccess (Configuración de Apache)
|-- sql/ (Scripts para configurar la base de datos)
|-- src/
|   |-- Controllers/ (Controladores de la aplicación)
|   |-- Models/ (Lógica de negocio y acceso a datos)
|   |-- Views/ (Plantillas para la interfaz de usuario)
|   |-- Route.php (Gestión de rutas de la aplicación)
|-- tests/ (Pruebas unitarias)
|-- vendor/ (Dependencias externas de Composer)
|-- .gitignore/ (Ficheros y directorios ignorados por Git)
|-- composer.json (Configuración de dependencias)
|-- LICENSE.txt (Términos de licencia)
|-- README.md (Documentación del proyecto)
```

## 📜 Licencia

Este proyecto está disponible bajo la licencia [MIT](LICENSE.txt).

## 👨‍💻 Autor

**Desarrollado por:**  
**Manuel Luis Muñoz Sánchez**  
[GitHub](https://github.com/ManuelLMunoz)