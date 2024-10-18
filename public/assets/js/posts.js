$(document).ready(function () {

    // ----------------------------------------------------------
    // Ajustar la altura de los textareas y resetear su contenido
    // ----------------------------------------------------------
    const autoExpand = (event) => {
        const element = event.target;
        // Ajustar la altura del textarea a la altura del contenido
        if (element) {
            element.style.height = `${Math.max(element.scrollHeight, 45)}px`;
        }
    };

    const resetTextarea = (textarea) => {
        textarea.value = "";
        // Ajustar la altura a auto para recalcular la altura adecuada
        textarea.style.height = "auto";
        // Llamar a autoExpand para establecer la altura adecuada
        autoExpand({ target: textarea });
    };

    // Listener de delegación para el evento "input" en todos los elementos textarea
    $(document).on("input", "textarea", function (event) {
        autoExpand(event);
    });

    // Ajustar la altura de todos los textareas al cargar la página
    window.addEventListener("load", () => {
        document.querySelectorAll("textarea").forEach(autoExpand);
    });

    // -------------------------------------------------------------------
    // Mostar los géneros de la BBDD en los select de crear y editar posts
    // -------------------------------------------------------------------
    const loadGenres = async (selectedGenre, selectId, isEditing = false) => {
        const { genres: options } = await $.post("get-genres");
        const defaultGenre = options.find(option => option.name === "General")?.id;
        // Si se crea un post, el valor por defecto es "General"
        const genreToSelect = isEditing ? options.find(option => option.name === selectedGenre)?.id : selectedGenre || defaultGenre;
        // Si se edita un post, por defecto se muestra el género de dicho post
        $(`#${selectId}`).html(options.map(option =>
            `<option value="${option.id}" ${option.id === genreToSelect ? "selected" : ""}>${option.name}</option>`
        ).join(""));
    };

    // Cargar y obtener el género seleccionado en el formulario de crear post
    loadGenres($("#post-genre").data("selected"), "post-genre");

    // ------------------------------------
    // Crear y mostrar los mensajes "toast"
    // ------------------------------------
    const showToast = (message, type) => {
        const toast = document.createElement("div");
        toast.className = `toast ${type}`;
        toast.innerText = message;
        document.body.appendChild(toast);

        // Mostrar el toast y ocultarlo tras 3 segundos
        setTimeout(() => toast.style.opacity = 1, 10);
        setTimeout(() => { toast.style.opacity = 0; setTimeout(() => toast.remove(), 500); }, 3000);
    };

    // ----------------------------
    // Ampliar el post seleccionado
    // ----------------------------
    const toggleZoom = (post, zoomIn) => {
        // Alternar la clase "zoom" y visibilidad de elementos
        $(post).toggleClass("zoom", zoomIn);
        $(post).find(".close-button").css("display", zoomIn ? "block" : "none");
        $("body").toggleClass("modal-open", zoomIn);
        $(post).find(".manage-posts").css("display", zoomIn ? "grid" : "none");

        // Mostrar/ocultar la sección de comentarios
        $(post).find(".post-comments, .comment-form").css("display", zoomIn ? "block" : "none");
    };

    // Manejar el clic en un post
    $(document).on("click", ".post-info", (event) => {
        // Abrir el post si el clic no es en el botón de like
        if (!$(event.target).closest(".like-button").length) {
            toggleZoom(event.currentTarget, true);
        }
    });

    // Botón de cierre del post
    //-------------------------
    $(document).on("click", ".close-button", (event) => {

        event.stopPropagation();
        const post = $(event.currentTarget).closest(".post-info")[0];

        // Restaurar el contenido original del post
        restoreOriginalContent(post);

        // Mostrar los botones y elementos ocultos del post
        [".edit-button", ".delete-button", ".post-community"].forEach(selector => {
            const element = post.querySelector(selector);
            if (element) element.style.display = "initial";
        });

        // Reiniciar los campos de comentarios en el post
        $(post).find(".comment-form textarea").each((_, textarea) => resetTextarea(textarea));

        // Desactivar el zoom en el post
        toggleZoom(post, false);
    });

    // ----------------------
    // Modal para crear posts
    // ----------------------
    const toggleCreatePostModal = (open) => {
        $("#create-post-modal").toggleClass("hidden", !open);
        $("body").toggleClass("no-scroll", open);
    };

    // Abrir el modal al hacer clic en el botón de crear post
    $("#create-post-button").on("click", () => toggleCreatePostModal(true));

    // Cerrar el modal y restablecer el formulario al cerrar o cancelar
    $("#close-popup, #cancel-create-post").on("click", () => {
        toggleCreatePostModal(false);
        $("#create-post-form")[0].reset();
        $("#form-errors").empty();
        $("#create-post-form textarea").each((_, textarea) => resetTextarea(textarea));// Resetear el textarea
    });

    // Enviar el formulario al hacer submit
    $("#create-post-form").on("submit", function (event) {
        event.preventDefault();

        // Recoger los datos del formulario en un objeto de serialización
        const formData = $(this).serialize();

        // Petición POST
        $.post($(this).attr('action'), formData, function (data) {
            if (data.success) {
                // Se crea el post, muestra un mensaje y carga la primera página
                showToast(data.message, "success");
                toggleCreatePostModal(false);
                $("#create-post-form")[0].reset();
                $("#create-post-form textarea").each((_, textarea) => resetTextarea(textarea));
                window.myApp?.load_data?.(1);
            }
        }, 'json').fail(function () {
            showToast("Error al crear el post. Inténtalo de nuevo.", "error");
        });
    });

    // -------------------------------------
    // Modal para borrar posts y comentarios
    // -------------------------------------
    $(document).on("click", (event) => {

        const $deleteButton = $(event.target).closest(".delete-button, .delete-comment-button");
        if (!$deleteButton.length) return;

        event.preventDefault();
        const idToDelete = $deleteButton.data("id");
        const deleteType = $deleteButton.hasClass("delete-button") ? "post" : "comment";
        const postIdToUpdate = deleteType === "comment" ? $deleteButton.closest(".post-info").attr("id").replace("post-", "") : null;

        $("#delete-message").text(`¿Está seguro de borrar este ${deleteType === "post" ? "post" : "comentario"}?`);
        $("#delete-modal").removeClass("hidden").data({ idToDelete, deleteType, postIdToUpdate });
    });

    // Cancelar el borrado
    $("#cancel-delete").on("click", () => {
        $("#delete-modal").addClass("hidden").removeData();
    });

    // Confirmar el borrado
    $("#confirm-delete").on("click", function () {
        const $modal = $("#delete-modal");
        const idToDelete = $modal.data("idToDelete");
        const deleteType = $modal.data("deleteType");
        const postIdToUpdate = $modal.data("postIdToUpdate");

        if (!idToDelete) {
            showToast("Error al borrar el elemento seleccionado.", "error");
            $modal.addClass("hidden");
            return;
        }

        // URL y datos según el tipo de eliminación
        const url = `/delete-${deleteType}/${idToDelete}`;
        const data = deleteType === "comment" ? { post_id: postIdToUpdate } : {};

        // Petición al servidor
        $.post(url, data, function (result) {
            if (result.success) {
                if (deleteType === "post") {
                    // Eliminar el post y cargar la primera página
                    const $zoomPost = $(".post-info.zoom");
                    if ($zoomPost.length && $zoomPost.attr("id") === `post-${idToDelete}`) {
                        toggleZoom($zoomPost[0], false);
                    }
                    $(`#post-${idToDelete}`).remove();
                    window.myApp?.load_data?.(1);
                } else if (deleteType === "comment") {
                    // Eliminar el comentario y actualizar el post
                    $(`#comment-${idToDelete}`).remove();
                    updateComments(postIdToUpdate);
                }
                showToast(result.message, "success");
            } else {
                showToast(result.message, "error");
            }
        }, "json").fail(function () {
            showToast("Error al procesar la respuesta del servidor.", "error");
        }).always(function () {
            // Ocultar el modal y eliminar los datos guardados
            $modal.addClass("hidden").removeData();
        });
    });

    // ----------------------------------------
    // Restaurar el contenido original del post
    // ----------------------------------------

    // Manejar los caracteres especiales
    const escapeHtml = (text) => text
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const unescapeHtml = (text) => text
        .replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&quot;/g, '"')
        .replace(/&#039;/g, "'");

    const restoreOriginalContent = (post) => {
        // Obtener el contenido original
        const $post = $(post);
        const originalContent = $post.attr("data-original-content");
        const originalTitle = $post.attr("data-original-title");
        const originalGenre = $post.attr("data-original-genre");
        const originalLanguage = $post.attr("data-original-language");

        // Verificar y actualizar el contenido del post
        if (originalContent && originalTitle) {
            $post.find(".post-details").html(`<p><strong>Categoría:</strong> ${originalGenre}</p><p><strong>Idioma:</strong> ${originalLanguage}</p>`);
            $post.find(".post-content").html(`<h2>${escapeHtml(originalTitle)}</h2><p class="formatted-text">${escapeHtml(originalContent)}</p>`);
        }

        // Eliminar los formularios de respuesta y restablecer el botón
        $post.find(".comment-form:not(#comment-form)").remove();
        $post.find(".reply-button").removeClass("form-opened");
    };

    // -----------------------
    // Modal para editar posts
    // -----------------------
    $(document).on("click", ".edit-button", async (event) => {
        event.preventDefault();

        // Extraer la información del post sin etiquetas HTML
        const postContainer = $(event.currentTarget).closest(".post-info");
        const postId = postContainer.attr("id").replace("post-", "");
        const postTitle = postContainer.find(".post-content h2").text();
        const postContent = postContainer.find(".post-content p").text();
        const postGenre = postContainer.find(".post-details p").eq(0).text().replace("Categoría:", "").trim();
        const postLanguage = postContainer.find(".post-details p").eq(1).text().replace("Idioma:", "").trim();

        // Guardar el contenido original
        postContainer.attr("data-original-title", postTitle);
        postContainer.attr("data-original-content", postContent);
        postContainer.attr("data-original-genre", postGenre);
        postContainer.attr("data-original-language", postLanguage);

        // Ocultar los botones y comentarios
        postContainer.find(".edit-button, .delete-button, .post-community").hide();

        // Generar el HTML para editar los campos
        postContainer.find(".post-details").html(`
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
        `);

        postContainer.find(".post-content").html(`
        <input type="text" id="edit-title-${postId}" name="title" placeholder="Título" required value="${escapeHtml(postTitle)}">
        <textarea id="edit-content-${postId}" name="content" placeholder="Contenido" required>${escapeHtml(postContent)}</textarea>
        <button type="button" id="confirm-edit" class="confirm-button" data-id="${postId}">Publicar</button>
        <button type="button" class="cancel-button" data-id="${postId}">Cancelar</button>
        `);

        // Cargar y obtener el género seleccionado de la lista
        await loadGenres(postGenre, `edit-genre-${postId}`, true);

        // Llamada inicial para ajustar la altura
        const textarea = document.getElementById(`edit-content-${postId}`);
        textarea.addEventListener("input", autoExpand);
        autoExpand({ target: textarea });
    });

    // Cancelar edición
    $(document).on("click", ".cancel-button", function () {
        const post = $(this).closest(".post-info");
        restoreOriginalContent(post);
        // Restaurar los botones y comentarios
        [".edit-button", ".delete-button", ".post-community"].forEach(selector => {
            post.find(selector).css("display", "initial");
        });
        post.find(".post-content textarea").each((_, textarea) => resetTextarea(textarea));
    });

    // Guardar edición
    $(document).on("click", "#confirm-edit", async function () {
        // Obtener los valores del post
        const postId = $(this).data("id");
        const post = this.closest(".post-info");
        const fields = ["title", "content", "genre", "language"];
        const genreName = $(`#edit-genre-${postId} option:selected`).text();
        const data = { post_id: postId };

        // Almacenar los valores de los campos
        fields.forEach(field => {
            data[field] = unescapeHtml($(`#edit-${field}-${postId}`).val());
        });

        // Petición al servidor
        $.post(`/edit-post/${postId}`, JSON.stringify(data), function (result) {
            if (result.success) {
                showToast(result.message, "success");
                // Actualizar los datos del post
                Object.assign(post.dataset, {
                    originalTitle: data.title,
                    originalContent: data.content,
                    originalGenre: genreName,
                    originalLanguage: data.language
                });
                // Restaurar el contenido original del post
                restoreOriginalContent(post);
                // Mostrar nuevamente los botones
                [".edit-button", ".delete-button", ".post-community"].forEach(selector => {
                    const element = post.querySelector(selector);
                    if (element) element.style.display = "initial";
                });

                $(post).find(".post-content textarea").each((_, textarea) => resetTextarea(textarea));
            } else {
                showToast(result.message, "error");
            }
        }, "json").fail(function () {
            showToast("Error al actualizar el post. Inténtalo de nuevo.", "error");
        });
    });

    // ----------------------------------------------------------
    // Comentarios de los posts
    // ----------------------------------------------------------

    // Agregar comentario
    $(document).on("click", "#confirm-comment", async function (event) {
        event.preventDefault();

        // Obtener referencias y datos del formulario
        const form = $(event.currentTarget).closest("form");
        const formData = form.serialize();
        const postId = form.find("input[name='post_id']").val();
        const textarea = form.find("textarea[name='comment']")[0];

        // Prevenir múltiples envíos deshabilitando el botón
        const submitButton = $(event.currentTarget);
        if (submitButton.prop("disabled")) return;
        submitButton.prop("disabled", true);

        // Petición al servidor
        $.post("/submit-comment", formData, function (result) {
            if (result.success) {
                // Limpiar el formulario, actualizar la lista de comentarios y mostrar un mensaje de éxito
                form[0].reset();
                resetTextarea(textarea);
                updateComments(postId);
                showToast("Comentario agregado con éxito.", "success");
            } else {
                showToast(result.error, "error");
            }
        }, "json").fail(function () {
            showToast("Error al agregar el comentario. Inténtalo de nuevo.", "error");
        }).always(function () {
            submitButton.prop("disabled", false);
        });
    });

    // Actualizar comentarios
    const updateComments = (postId) => {
        $.post("/fetch-comments", { post_id: postId }, (result) => {
            if (result.success) {
                // Actualizar los comentarios y el contador
                const $post = $(`#post-${postId}`);
                $post.find(".post-comments").html(result.html);
                $post.find(".comment-button span").text(result.comments_count);

                // Mostrar y resetear el formulario de comentarios
                const $commentForm = $post.find(".comment-form").show();
                $commentForm.find("textarea").val("");
                $commentForm.find(".confirm-button, .cancel-button").addClass("hidden");

            } else {
                showToast(result.error, "error");
            }
        }, "json").fail(() => {
            showToast("Error al actualizar los comentarios. Inténtalo de nuevo.", "error");
        });
    };

    // Mostrar/ocultar los botones de los comentarios según el contenido del textarea
    const toggleButtonsVisibility = (textarea) => {
        const buttons = textarea.parentElement.querySelectorAll(".confirm-button, .cancel-button");
        buttons.forEach(button => button.classList.toggle("hidden", !textarea.value.trim()));
    };

    // Cancelar comentario
    $(document).on("click", "#cancel-comment", function () {
        const textarea = this.closest("form").querySelector("textarea");
        if (textarea) {
            // Restablecer el textarea y volver a mostrar los botones
            resetTextarea(textarea);
            toggleButtonsVisibility(textarea);
        }
    });

    // Verificar si el usuario está logado al intentar comentar
    $(document).on("focus", "textarea[name='comment']", function () {
        if (!$(this).closest(".comment-form").data("logged-in")) {
            showToast("Debes iniciar sesión para comentar", "error");
            $(this).blur();
        }
    });

    // Actualizar la visibilidad de los botones al comentar
    $(document).on("input", "textarea[name='comment']", function () {
        toggleButtonsVisibility(this);
    });

    // -------------------------------
    // Editar comentarios de los posts
    // -------------------------------
    $(document).on("click", ".edit-comment-button", (event) => {
        event.preventDefault();

        // Obtener solo el comentario padre, excluyendo los anidados
        const $comment = $(event.currentTarget).closest(".comment");
        const id = $comment.attr("id").split("-")[1];
        const $commentContent = $comment.children(".comment-content").first();

        // Obtener el contenido HTML sin procesar
        const content = $commentContent.html();

        // Generar el HTML para editar el comentario
        $comment.data("original-html", $comment.html()).html(`
            <div class="user-data">${$comment.find(".user-data").html()}</div>
            <div class="form-group">
                <textarea id="edit-content-${id}" name="content" placeholder="Contenido" required>${content}</textarea>
                <button type="button" id="confirm-edit-button" class="confirm-button" data-id="${id}">Guardar</button>
                <button type="button" id="cancel-edit-button" class="cancel-button" data-id="${id}">Cancelar</button>
            </div>
        `);

        // Ajustar la altura del textarea editado
        const editedTextarea = $(`#edit-content-${id}`);
        editedTextarea.on("input", autoExpand).trigger("input");
    });

    // Cancelar la edición
    $(document).on("click", "#cancel-edit-button", (event) => {
        const $comment = $(event.currentTarget).closest(".comment");
        $comment.html($comment.data("original-html")).removeData("original-html");
    });

    // Confirmar edición
    $(document).on("click", "#confirm-edit-button", function () {
        // Obtener los datos del post
        const id = $(this).data("id");
        const content = $(`#edit-content-${id}`).val();
        const postId = $(this).closest(".post-info").attr("id").split("-")[1];

        // Petición al servidor
        $.post(`/edit-comment/${id}`, JSON.stringify({ comment_id: id, content }), function (result) {
            if (result.success) {
                // Mostrar un mensaje y actualizar los comentarios del post
                showToast(result.message, "success");
                updateComments(postId);
            } else {
                showToast(result.message, "error");
            }
        }, "json").fail(function () {
            showToast("Error al actualizar el comentario. Inténtalo de nuevo.", "error");
        });
    });

    // ------------------------------------
    // Botón de respuesta a los comentarios
    // ------------------------------------
    const setupReplyComments = () => {
        $(document).on("click", ".reply-button", function () {

            // Evitar que se abra el formulario si ya lo está
            if ($(this).hasClass("form-opened")) return;

            $(this).addClass("form-opened");

            // Obtener el ID del comentario padre y el ID del post
            const parentId = $(this).data("parent-id");
            const postId = $(this).closest(".post-info").attr("id").split("-")[1];
            const $form = $("#comment-form").clone().removeAttr("id").show();

            // Configurar el formulario con los IDs correspondientes
            $form.find("#parent_comment_id").val(parentId);
            $form.find("input[name='post_id']").val(postId);
            $form.find("textarea").val("").on("input", toggleButtonsVisibility)
                .each((_, textarea) => {
                    autoExpand({ target: textarea });
                    textarea.addEventListener("input", autoExpand);
                });

            // Añadir el formulario clonado al comentario padre
            $(`#comment-${parentId}`).append($form);
        });
    };

    setupReplyComments();

    // ---------------------------------------
    // Gestión de likes en posts y comentarios
    // ---------------------------------------
    $(document).on("click", ".like-button", function (event) {
        event.stopPropagation();
        const button = $(event.currentTarget);

        // Verificar y deshabilitar el botón si no está deshabilitado
        if (button.is(":disabled")) return button.prop("disabled", true);

        // Obtener el tipo de like e ID del elemento
        const type = button.data("type");
        const id = button.data("id");

        // Petición POST en función del tipo de like
        $.post(`/like-${type}`, `${type}_id=${id}`, null, "json")
            .done(function (response) {
                if (response.success) {
                    // Actualizar el contador de likes según la acción
                    const likeCountSpan = button.find("span");
                    const currentLikes = parseInt(likeCountSpan.text(), 10);
                    likeCountSpan.text(response.action === "liked" ? currentLikes + 1 : currentLikes - 1);
                    button.toggleClass("liked");
                } else if (response.error) {
                    showToast(response.error, "error");
                }
            })
            .fail(function () {
                showToast("Error al procesar el like. Inténtalo de nuevo.", "error");
            })
            .always(function () {
                button.prop("disabled", false);
            });
    });
});



