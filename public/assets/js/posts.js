document.addEventListener("DOMContentLoaded", () => {

    // ------------------------------
    // Configuración de los textareas
    // ------------------------------
    const autoExpand = ({ target }) => {
        if (target) { target.style.height = `${Math.max(target.scrollHeight, 45)}px`; }
    };

    const resetTextarea = (textarea) => {
        textarea.value = "";
        textarea.style.height = "auto";
        autoExpand({ target: textarea });
    };

    // Ajustar la altura de los textareas al escribir
    document.querySelectorAll("textarea").forEach(textarea => textarea.addEventListener("input", autoExpand));

    window.addEventListener("load", () => document.querySelectorAll("textarea").forEach(autoExpand));

    // -------------------------------------------------------------------
    // Mostar los géneros de la BBDD en los select de crear y editar posts
    // -------------------------------------------------------------------
    const loadGenres = async (selectedGenre, selectId, isEditing = false) => {
        const response = await fetch("get-genres", { method: "POST" });
        const { genres: options } = await response.json();
        const defaultGenre = options.find(option => option.name === "General")?.id;
        // Si se crea un post, mostrar "General" y si se edita un post, mostrar el género de dicho post
        const genreToSelect = isEditing ? options.find(option => option.name === selectedGenre)?.id : selectedGenre || defaultGenre;
        document.getElementById(selectId).innerHTML = options.map(option => {
            return `<option value="${option.id}" ${option.id === genreToSelect ? "selected" : ""}>${option.name}</option>`;
        }).join("");
    };

    // ----------------------------
    // Ampliar el post seleccionado
    // ----------------------------
    let previousURL = null;
    let isZooming = false;

    const toggleZoom = (post, zoomIn) => {

        if (isZooming) return;
        isZooming = true;

        // Almacenar la URL actual antes de abrir el modal
        if (zoomIn) { previousURL = window.location.href; }

        // Alternar la clase "zoom" y visibilidad de elementos
        post.classList.toggle("zoom", zoomIn);
        document.body.classList.toggle("modal-open", zoomIn);
        const displayValue = zoomIn ? "block" : "none";
        post.querySelector(".close-button")?.style.setProperty("display", displayValue);
        post.querySelector(".manage-posts")?.style.setProperty("display", zoomIn ? "grid" : "none");
        post.querySelectorAll(".post-comments, .comment-form").forEach(comment => {
            comment.style.display = displayValue;
        });

        // Obtener el ID y título del post
        const postId = post.id.split("-")[1];
        const postTitle = sanitizeTitle(post.querySelector(".post-content h2").textContent.trim());

        // Actualizar la URL con el ID y título del post ampliado o restaurar la URL original
        history.pushState(null, null, zoomIn ? `/posts/${postId}/${postTitle}` : previousURL);
    };

    // Restaurar la URL anterior o redirigir a la lista de posts
    window.addEventListener("popstate", () => {
        const currentPath = window.location.pathname;
        if (!currentPath.startsWith("/posts/") || currentPath.split("/").length <= 2) {
            window.location.href = previousURL || "/posts";
        } else {
            const postId = currentPath.split("/")[2];
            const postTitle = currentPath.split("/")[3];
            loadPostById(postId, postTitle);
        }
    });

    const loadPostById = (postId, postTitle = null) => {
        const url = postTitle ? `/posts/${postId}/${postTitle}` : `/posts/${postId}`;
        window.location.href = url;
    };

    document.addEventListener("click", (event) => {
        const postInfo = event.target.closest(".post-info:not(.details)");
        // Verificar si el clic fue dentro de un elemento .post-info y no en el botón de like
        if (postInfo && !event.target.closest(".like-button")) {
            toggleZoom(postInfo, true);
        }
    });

    document.addEventListener("click", (event) => {
        const post = event.target.closest(".close-button")?.closest(".post-info");
        if (!post) return;

        event.stopPropagation();

        if (isZooming) {
            restoreOriginalContent(post);
            post.querySelectorAll(".edit-button, .delete-button, .post-community")
                .forEach(el => el.style.display = "initial");

            post.querySelectorAll(".comment-form textarea")
                .forEach(resetTextarea);

            isZooming = false;
        }

        toggleZoom(post, false);
        history.pushState(null, null, previousURL || "/posts");
        isZooming = false;
    });

    const sanitizeTitle = (title) => {
        return title
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .replace(/[^a-z0-9 ]/g, "")
            .replace(/\s+/g, "-")
            .replace(/^-+|-+$/g, "");
    };

    // ----------------------
    // Modal para crear posts
    // ----------------------
    const toggleCreatePostModal = open => {
        document.querySelector("#create-post-modal").classList.toggle("hidden", !open);
        document.body.classList.toggle("no-scroll", open);
        // Mostrar los géneros ("General" por defecto)
        loadGenres(document.getElementById("post-genre").dataset.selected, "post-genre");
    };

    document.getElementById("create-post-button")?.addEventListener("click", () => toggleCreatePostModal(true));

    document.querySelectorAll("#close-popup, #cancel-create-post").forEach(button => {
        button.addEventListener("click", () => {
            toggleCreatePostModal(false);
            document.getElementById("create-post-form").reset();
            document.querySelectorAll("#create-post-form textarea").forEach(textarea => resetTextarea(textarea));
        });
    });

    // Enviar el formulario al hacer submit
    document.querySelector("#create-post-form")?.addEventListener("submit", async (event) => {
        event.preventDefault();
        try {
            const response = await fetch(event.target.action, {
                method: "POST",
                body: new FormData(event.target), // Recoger los datos del formulario
            });
            const data = await response.json();
            if (data.success) {
                showToast(data.message, "success");
                toggleCreatePostModal(false);
                event.target.reset();
                event.target.querySelectorAll("textarea").forEach(resetTextarea);
                window.myApp?.load_data?.(1);
            }
        } catch {
            showToast("Error al crear el post. Inténtalo de nuevo.", "error");
        }
    });

    // -------------------------------------
    // Modal para borrar posts y comentarios
    // -------------------------------------
    document.addEventListener("click", (event) => {
        const deleteButton = event.target.closest(".delete-button, .delete-comment-button");
        if (!deleteButton) return;

        event.preventDefault();
        const idToDelete = deleteButton.dataset.id;
        const deleteType = deleteButton.classList.contains("delete-button") ? "post" : "comment";
        const postIdToUpdate = deleteType === "comment" ? deleteButton.closest(".post-info").id.replace("post-", "") : null;

        const modal = document.getElementById("delete-modal");
        modal.classList.remove("hidden");
        modal.dataset.idToDelete = idToDelete;
        modal.dataset.deleteType = deleteType;
        modal.dataset.postIdToUpdate = postIdToUpdate;
        document.getElementById("delete-message").textContent = `¿Está seguro de borrar este ${deleteType === "post" ? "post" : "comentario"}?`;
    });

    document.getElementById("cancel-delete").addEventListener("click", () => {
        const modal = document.getElementById("delete-modal");
        modal.classList.add("hidden");
        delete modal.dataset.idToDelete;
        delete modal.dataset.deleteType;
        delete modal.dataset.postIdToUpdate;
    });

    document.getElementById("confirm-delete").addEventListener("click", async () => {
        const modal = document.getElementById("delete-modal");
        const { idToDelete, deleteType, postIdToUpdate } = modal.dataset;

        if (!idToDelete) {
            showToast("Error al borrar el elemento seleccionado.", "error");
            modal.classList.add("hidden");
            return;
        }

        // URL y datos según el tipo de eliminación
        const url = `/delete-${deleteType}/${idToDelete}`;
        const data = deleteType === "comment" ? { post_id: postIdToUpdate } : {};

        try {
            const response = await fetch(url, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                if (deleteType === "post") {
                    document.getElementById(`post-${idToDelete}`).remove();
                    // Si estamos en la página de detalles, volver a /posts tras borrar
                    if (window.location.pathname.startsWith("/posts/")) {
                        localStorage.setItem("deleteSuccessMessage", result.message);
                        window.location.href = "/posts";
                    } else {
                        // En otro contexto, cargar la primera página
                        window.myApp?.load_data?.(1);
                    }
                } else if (deleteType === "comment") {
                    document.getElementById(`comment-${idToDelete}`).remove();
                    showToast(result.message, "success");
                    updateComments(postIdToUpdate);
                }
            } else {
                showToast(result.message, "error");
            }
        } catch (error) {
            showToast("Error al procesar la respuesta del servidor.", "error");
        } finally {
            // Ocultar el modal y eliminar los datos guardados
            modal.classList.add("hidden");
            delete modal.dataset.idToDelete;
            delete modal.dataset.deleteType;
            delete modal.dataset.postIdToUpdate;
        }
    });

    // Mostrar el mensaje de borrado si lo hay 
    const deleteSuccessMessage = localStorage.getItem("deleteSuccessMessage");
    if (deleteSuccessMessage) {
        showToast(deleteSuccessMessage, "success");
        localStorage.removeItem("deleteSuccessMessage");
    }

    // ----------------------------------------
    // Restaurar el contenido original del post
    // ----------------------------------------

    // Manejar los caracteres especiales
    const escapeHtml = (text) => {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    };

    const unescapeHtml = (text) => {
        return text
            .replace(/&amp;/g, "&")
            .replace(/&lt;/g, "<")
            .replace(/&gt;/g, ">")
            .replace(/&quot;/g, "\"")
            .replace(/&#039;/g, "'");
    };
    const restoreOriginalContent = (post) => {
        // Verificar que el post es un nodo del DOM

        if (!(post instanceof Element)) return;

        // Recuperar el contenido original del post
        const originalContent = post.getAttribute("data-original-content");
        const originalTitle = post.getAttribute("data-original-title");
        const originalGenre = post.getAttribute("data-original-genre");
        const originalLanguage = post.getAttribute("data-original-language");

        // Verificar y actualizar el contenido del post
        if (originalContent && originalTitle) {
            post.querySelector(".post-details").innerHTML = `<p><strong>Categoría:</strong> ${originalGenre}</p><p><strong>Idioma:</strong> ${originalLanguage}</p>`;
            post.querySelector(".post-content").innerHTML = `<h2>${escapeHtml(originalTitle)}</h2><p class="formatted-text">${escapeHtml(originalContent)}</p>`;
        }

        // Eliminar los formularios de respuesta y restablecer el botón
        post.querySelectorAll(".comment-form:not(#comment-form)").forEach(form => form.remove());
        post.querySelector(".reply-button")?.classList.remove("form-opened");
    };

    // -----------------------
    // Modal para editar posts
    // -----------------------
    document.addEventListener("click", async (event) => {
        if (event.target.closest(".edit-button")) {
            event.preventDefault();
            isZooming = true;

            // Extraer la información del post sin etiquetas HTML
            const postContainer = event.target.closest(".post-info");
            const postId = postContainer.id.replace("post-", "");
            const postTitle = postContainer.querySelector(".post-content h2").textContent;
            const postContent = postContainer.querySelector(".post-content p").textContent;
            const postGenre = postContainer.querySelector(".post-details p:nth-of-type(1)").textContent.replace("Categoría:", "").trim();
            const postLanguage = postContainer.querySelector(".post-details p:nth-of-type(2)").textContent.replace("Idioma:", "").trim();

            // Guardar el contenido original
            postContainer.setAttribute("data-original-title", postTitle);
            postContainer.setAttribute("data-original-content", postContent);
            postContainer.setAttribute("data-original-genre", postGenre);
            postContainer.setAttribute("data-original-language", postLanguage);

            postContainer.querySelectorAll(".edit-button, .delete-button, .post-community").forEach(button => button.style.display = "none");

            // Generar el HTML para editar los campos
            postContainer.querySelector(".post-details").innerHTML = `
            <div class="form-group">
                <label for="edit-genre-${postId}"><strong>Categoría:</strong></label>
                <select id="edit-genre-${postId}" name="genre" required></select>
            </div>
            <div class="form-group">
                <label for="edit-language-${postId}"><strong>Idioma:</strong></label>
                <select id="edit-language-${postId}" name="language" required>
                    <option value="Español" ${postLanguage === "Español" ? "selected" : ""}>Español</option>
                    <option value="Inglés" ${postLanguage === "Inglés" ? "selected" : ""}>Inglés</option>
                </select>
            </div>
            `;

            postContainer.querySelector(".post-content").innerHTML = `
            <input type="text" id="edit-title-${postId}" name="title" placeholder="Título" required value="${escapeHtml(postTitle)}">
            <textarea id="edit-content-${postId}" name="content" placeholder="Contenido" required>${escapeHtml(postContent)}</textarea>
            <button type="button" id="confirm-edit" class="confirm-button" data-id="${postId}">Publicar</button>
            <button type="button" class="cancel-button" data-id="${postId}">Cancelar</button>
            `;

            await loadGenres(postGenre, `edit-genre-${postId}`, true);

            // Llamada inicial para ajustar la altura
            const textarea = document.getElementById(`edit-content-${postId}`);
            textarea.addEventListener("input", autoExpand);
            autoExpand({ target: textarea });
        }
    });

    document.addEventListener("click", (event) => {
        if (event.target.matches(".cancel-button")) {
            const post = event.target.closest(".post-info");
            if (!post) return;
            restoreOriginalContent(post);

            // Restaurar botones, comentarios y estado de edición
            post.querySelectorAll(".edit-button, .delete-button, .post-community").forEach(element => element.style.display = "initial");
            post.querySelectorAll(".post-content textarea").forEach(resetTextarea);
            // Aunque editar se cierre, todavia el post está zoomeado
            isZooming = true;
        }
    });

    document.addEventListener("click", async (event) => {
        if (event.target.id === "confirm-edit") {
            // Obtener los valores del post
            const postId = event.target.dataset.id;
            const post = event.target.closest(".post-info");
            const genreName = document.querySelector(`#edit-genre-${postId} option:checked`).textContent;

            // Almacenar los valores de los campos editados
            const data = {
                post_id: postId,
                title: unescapeHtml(document.querySelector(`#edit-title-${postId}`).value),
                content: unescapeHtml(document.querySelector(`#edit-content-${postId}`).value),
                genre: unescapeHtml(document.querySelector(`#edit-genre-${postId}`).value),
                language: unescapeHtml(document.querySelector(`#edit-language-${postId}`).value)
            };

            try {
                const response = await fetch(`/edit-post/${postId}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                if (result.success) {
                    showToast(result.message, "success");
                    // Actualizar los datos del post
                    Object.assign(post.dataset, {
                        originalTitle: data.title,
                        originalContent: data.content,
                        originalGenre: genreName,
                        originalLanguage: data.language
                    });
                    restoreOriginalContent(post); // Pasar de contenido editable a contenido visual
                    document.querySelectorAll(".edit-button, .delete-button, .post-community").forEach(element => {
                        element.style.display = "initial";
                    });

                    // Actualizar la URL con el nuevo título
                    history.replaceState(null, null, `/posts/${postId}/${sanitizeTitle(data.title)}`);

                } else {
                    showToast(result.message, "error");
                }
            } catch (error) {
                showToast("Error al actualizar el post. Inténtalo de nuevo.", "error");
            }
        }
    });

    // ------------------------
    // Comentarios de los posts
    // ------------------------
    document.addEventListener("click", async (event) => {
        if (event.target.id !== "confirm-comment") return;
        event.preventDefault();

        const form = event.target.closest("form");
        event.target.disabled = true;

        try {
            const response = await fetch("/submit-comment", {
                method: "POST",
                body: new URLSearchParams(new FormData(form)), // Recoger los datos del formulario
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            });

            const data = await response.json();

            if (data.success) {
                // Limpiar el formulario y actualizar los comentarios
                form.reset();
                resetTextarea(form.querySelector("textarea[name='comment']"));
                updateComments(form.querySelector("[name='post_id']").value);
                showToast("Comentario agregado con éxito.", "success");
            } else {
                showToast(data.error, "error");
            }
        } catch {
            showToast("Error al agregar el comentario.", "error");
        } finally {
            event.target.disabled = false;
        }
    });

    const updateComments = async (postId) => {
        try {
            const response = await fetch("/fetch-comments", {
                method: "POST",
                body: new URLSearchParams({ post_id: postId }),
                headers: { "Content-Type": "application/x-www-form-urlencoded" }
            });

            const result = await response.json();

            if (result.success) {
                // Actualizar los comentarios y el contador
                const post = document.querySelector(`#post-${postId}`);
                post.querySelector(".post-comments").innerHTML = result.html;
                post.querySelector(".comment-button span").textContent = result.comments_count;

                // Mostrar y resetear el formulario de comentarios
                const commentForm = post.querySelector(".comment-form");
                commentForm.style.display = "block";
                commentForm.querySelector("textarea").value = "";
                commentForm.querySelectorAll(".confirm-button, .cancel-button")
                    .forEach(button => button.classList.add("hidden"));
            } else {
                showToast(result.error, "error");
            }
        } catch {
            showToast("Error al actualizar los comentarios. Inténtalo de nuevo.", "error");
        }
    };

    const toggleButtonsVisibility = textarea => {
        if (textarea.parentElement) {
            const buttons = textarea.parentElement.querySelectorAll(".confirm-button, .cancel-button");
            buttons.forEach(button => button.classList.toggle("hidden", !textarea.value.trim()));
        }
    };

    document.addEventListener("click", (event) => {
        if (event.target.id === "cancel-comment") {
            const textarea = event.target.closest("form")?.querySelector("textarea");
            if (textarea) {
                // Restablecer el textarea y volver a mostrar los botones
                toggleButtonsVisibility(textarea);
                resetTextarea(textarea);
            }
        }
    });

    // Verificar si el usuario está logado al intentar comentar
    document.addEventListener("focusin", (event) => {
        if (event.target.matches("textarea[name='comment']")) {
            const commentForm = event.target.closest(".comment-form");
            if (commentForm && commentForm.dataset.loggedIn !== "true") {
                showToast("Debes iniciar sesión para comentar", "error");
                event.target.blur();
            }
        }
    });

    // Actualizar la visibilidad de los botones al comentar
    document.addEventListener("input", (event) => event.target.matches("textarea[name='comment']") && toggleButtonsVisibility(event.target));

    // -------------------------------
    // Editar comentarios de los posts
    // -------------------------------
    document.addEventListener("click", (event) => {
        const button = event.target.closest(".edit-comment-button");
        if (!button) return;
        event.preventDefault();

        // Obtener solo el comentario padre
        const comment = event.target.closest(".comment");
        const id = comment.getAttribute("id").split("-")[1];
        const content = comment.querySelector(".comment-content").innerHTML;

        // Guardar el HTML original y reemplazar con el formulario de edición
        comment.dataset.originalHtml = comment.innerHTML;
        comment.innerHTML = `
                <div class="user-data">${comment.querySelector(".user-data").innerHTML}</div>
                <div class="form-group">
                    <textarea id="edit-content-${id}" name="content" placeholder="Contenido" required>${content}</textarea>
                    <button type="button" id="confirm-edit-button" class="confirm-button" data-id="${id}">Guardar</button>
                    <button type="button" id="cancel-edit-button" class="cancel-button" data-id="${id}">Cancelar</button>
                </div>
            `;

        const editedTextarea = document.getElementById(`edit-content-${id}`);
        editedTextarea.addEventListener("input", autoExpand);
        editedTextarea.dispatchEvent(new Event("input"));
    });

    document.addEventListener("click", (event) => {
        if (event.target?.id === "cancel-edit-button") {
            const comment = event.target.closest(".comment");
            comment.innerHTML = comment.dataset.originalHtml;
            comment.removeAttribute("data-original-html");
        }
    });

    document.addEventListener("click", async (event) => {
        if (event.target?.id === "confirm-edit-button") {
            // Obtener los datos del post
            const id = event.target.dataset.id;
            const content = document.querySelector(`#edit-content-${id}`).value;
            const postId = event.target.closest(".post-info").id.split("-")[1];

            try {
                const response = await fetch(`/edit-comment/${id}`, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ comment_id: id, content })
                });

                const result = await response.json();

                if (result.success) {
                    showToast(result.message, "success");
                    updateComments(postId);
                } else {
                    showToast(result.message, "error");
                }
            } catch (error) {
                showToast("Error al actualizar el comentario. Inténtalo de nuevo.", "error");
            }
        }
    });

    // ------------------------------------
    // Botón de respuesta a los comentarios
    // ------------------------------------
    const setupReplyComments = () => {
        document.addEventListener("click", (event) => {
            const button = event.target.closest(".reply-button");
            if (!button || button.classList.contains("form-opened")) return;

            button.classList.add("form-opened");

            // Obtener IDs y clonar el formulario
            const parentId = button.dataset.parentId;
            const postId = button.closest(".post-info").id.split("-")[1];
            const form = document.getElementById("comment-form").cloneNode(true);

            // Configurar el formulario y mostrarlo
            form.removeAttribute("id");
            form.style.display = "block";
            form.querySelector("[name='parent_comment_id']").value = parentId;
            form.querySelector("[name='post_id']").value = postId;

            // Añadir el formulario al comentario padre
            document.querySelector(`#comment-${parentId}`).append(form);
            form.querySelector("textarea").focus();

            // Manejar el botón de cancelar
            form.querySelector(".cancel-button").addEventListener("click", () => {
                form.remove();
                button.classList.remove("form-opened");
            });
        });
    };

    setupReplyComments();

    // ---------------------------------------
    // Gestión de likes en posts y comentarios
    // ---------------------------------------
    document.addEventListener("click", async (event) => {
        const button = event.target.closest(".like-button");
        if (!button || button.disabled) return;
        event.stopPropagation();

        // Deshabilitar el botón para evitar multiples clics
        button.disabled = true;

        // Obtener el tipo de like e ID del elemento
        const { type, id } = button.dataset;

        try {
            const response = await fetch(`/like-${type}`, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `${type}_id=${id}`,
            });

            const data = await response.json();

            if (data.success) {
                // Actualizar el contador de likes según la acción
                const likeCountSpan = button.querySelector("span");
                const currentLikes = parseInt(likeCountSpan.textContent, 10);
                likeCountSpan.textContent = data.action === "liked" ? currentLikes + 1 : currentLikes - 1;
                button.classList.toggle("liked");
            } else {
                showToast(data.error || "Error al procesar el like.", "error");
            }
        } catch {
            showToast("Error al procesar el like. Inténtalo de nuevo.", "error");
        } finally {
            button.disabled = false;
        }
    });
});