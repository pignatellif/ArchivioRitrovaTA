// Variabili globali per gli editor Quill
let quillAddEditor;
let quillEditEditors = {};
let sortable;

// Funzione per mostrare i campi del form di aggiunta
function showFields() {
    var type = document.getElementById("template_type").value;
    document
        .querySelectorAll(".template_fields")
        .forEach((div) => (div.style.display = "none"));
    document.getElementById("fields_" + type).style.display = "block";
}

// Funzione per mostrare il form di modifica
function editContent(contentId) {
    document.getElementById("edit-form-" + contentId).style.display = "block";
    // Inizializza Quill per l'editor di modifica se non già fatto
    if (!quillEditEditors[contentId]) {
        initializeEditQuill(contentId);
    }
}

// Funzione per nascondere il form di modifica
function cancelEdit(contentId) {
    document.getElementById("edit-form-" + contentId).style.display = "none";
}

// Funzione per mostrare i campi del form di modifica
function showEditFields(contentId, type) {
    document
        .querySelectorAll("#edit-form-" + contentId + " .edit-template-fields")
        .forEach((div) => (div.style.display = "none"));
    document.getElementById(
        "edit-fields-" + type + "-" + contentId
    ).style.display = "block";
}

// Inizializza Quill per il form di aggiunta
function initializeAddQuill() {
    quillAddEditor = new Quill("#content_testo", {
        theme: "snow",
        modules: {
            toolbar: [
                ["bold", "italic", "underline", "strike"],
                ["blockquote", "code-block"],
                [{ header: 1 }, { header: 2 }],
                [{ list: "ordered" }, { list: "bullet" }],
                [{ script: "sub" }, { script: "super" }],
                [{ indent: "-1" }, { indent: "+1" }],
                [{ direction: "rtl" }],
                [{ size: ["small", false, "large", "huge"] }],
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                [{ color: [] }, { background: [] }],
                [{ font: [] }],
                [{ align: [] }],
                ["link", "image"],
                ["clean"],
            ],
        },
        placeholder: "Scrivi il tuo testo qui...",
    });
}

// Inizializza Quill per il form di modifica
function initializeEditQuill(contentId) {
    const editContainer = document.getElementById(
        "edit_content_testo_" + contentId
    );
    if (editContainer) {
        quillEditEditors[contentId] = new Quill(
            "#edit_content_testo_" + contentId,
            {
                theme: "snow",
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"],
                        ["blockquote", "code-block"],
                        [{ header: 1 }, { header: 2 }],
                        [{ list: "ordered" }, { list: "bullet" }],
                        [{ script: "sub" }, { script: "super" }],
                        [{ indent: "-1" }, { indent: "+1" }],
                        [{ direction: "rtl" }],
                        [{ size: ["small", false, "large", "huge"] }],
                        [{ header: [1, 2, 3, 4, 5, 6, false] }],
                        [{ color: [] }, { background: [] }],
                        [{ font: [] }],
                        [{ align: [] }],
                        ["link", "image"],
                        ["clean"],
                    ],
                },
                placeholder: "Scrivi il tuo testo qui...",
            }
        );
    }
}

// Salva il nuovo ordine dei contenuti
function saveContentOrder() {
    const items = document.querySelectorAll(
        "#sortable-contents .sortable-item"
    );
    const contentIds = Array.from(items)
        .map((item) => (item ? item.getAttribute("data-content-id") : null))
        .filter((id) => id !== null && id !== undefined && id !== "");

    if (contentIds.length === 0) {
        console.log("Nessun contenuto da riordinare");
        return;
    }

    // Mostra un indicatore di caricamento
    showLoadingIndicator();

    // Ottieni il token CSRF dalla pagina
    let csrfToken = "";
    // Prova a ottenere il token dal meta tag
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    if (metaToken) {
        csrfToken = metaToken.getAttribute("content");
    } else {
        // Se non c'è il meta tag, prova a ottenerlo da un input hidden
        const hiddenToken = document.querySelector('input[name="_token"]');
        if (hiddenToken) {
            csrfToken = hiddenToken.value;
        } else {
            // Fallback: usa il token dalla variabile Blade
            csrfToken = "{{ csrf_token() }}";
        }
    }

    console.log("Invio richiesta con IDs:", contentIds);

    // Prendi la rotta dal data attribute del container, o fallback Blade
    const sortableContainer = document.getElementById("sortable-contents");
    const reorderUrl = sortableContainer
        ? sortableContainer.dataset.reorderUrl
        : "";

    // Invia la richiesta AJAX per salvare l'ordine
    fetch(reorderUrl, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify({
            content_ids: contentIds,
        }),
    })
        .then((response) => {
            console.log("Risposta ricevuta:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("Dati ricevuti:", data);
            hideLoadingIndicator();
            if (data.success) {
                showSuccessMessage(
                    "Ordine dei contenuti aggiornato con successo!"
                );
            } else {
                showErrorMessage(
                    data.message || "Errore durante il salvataggio dell'ordine."
                );
            }
        })
        .catch((error) => {
            console.error("Errore completo:", error);
            hideLoadingIndicator();
            showErrorMessage(
                "Errore durante il salvataggio dell'ordine: " + error.message
            );
        });
}

// Aggiorna i numeri di posizione visualizzati
function updatePositionNumbers() {
    const items = document.querySelectorAll(
        "#sortable-contents .sortable-item"
    );
    items.forEach((item, index) => {
        const positionSpan = item.querySelector(".position-number");
        if (positionSpan) {
            positionSpan.textContent = index + 1; // Inizia da 1, non da 0
        }
    });
}

// Inizializza SortableJS per il drag & drop
function initializeSortable() {
    const sortableContainer = document.getElementById("sortable-contents");
    if (sortableContainer && sortableContainer.children.length > 1) {
        sortable = Sortable.create(sortableContainer, {
            handle: ".drag-handle",
            animation: 150,
            ghostClass: "sortable-ghost",
            chosenClass: "sortable-chosen",
            dragClass: "sortable-drag",
            onStart: function (evt) {
                console.log("Inizio drag");
                evt.item.classList.add("dragging");
            },
            onEnd: function (evt) {
                console.log("Fine drag, da:", evt.oldIndex, "a:", evt.newIndex);
                evt.item.classList.remove("dragging");
                // Solo se la posizione è cambiata
                if (evt.oldIndex !== evt.newIndex) {
                    // Aggiorna i numeri di posizione visualizzati
                    updatePositionNumbers();
                    // Salva il nuovo ordine
                    saveContentOrder();
                }
            },
        });
        console.log(
            "SortableJS inizializzato con",
            sortableContainer.children.length,
            "elementi"
        );
    } else {
        console.log(
            "SortableJS non inizializzato: container non trovato o meno di 2 elementi"
        );
    }
}

// Mostra indicatore di caricamento
function showLoadingIndicator() {
    // Rimuovi eventuali indicatori esistenti
    hideLoadingIndicator();

    const indicator = document.createElement("div");
    indicator.id = "loading-indicator";
    indicator.className = "alert alert-info d-flex align-items-center";
    indicator.innerHTML = `
        <div class="spinner-border spinner-border-sm me-2" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        Salvataggio ordine in corso...
    `;

    const container = document.querySelector(".container-fluid");
    if (container) {
        container.insertBefore(indicator, container.firstChild);
    }
}

// Nascondi indicatore di caricamento
function hideLoadingIndicator() {
    const indicator = document.getElementById("loading-indicator");
    if (indicator) {
        indicator.remove();
    }
}

// Mostra messaggio di successo
function showSuccessMessage(message) {
    showMessage(message, "success");
}

// Mostra messaggio di errore
function showErrorMessage(message) {
    showMessage(message, "danger");
}

// Mostra messaggio generico
function showMessage(message, type) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `alert alert-${type} alert-dismissible fade show`;
    messageDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    const container = document.querySelector(".container-fluid");
    if (container) {
        container.insertBefore(messageDiv, container.firstChild);
    }

    // Rimuovi automaticamente dopo 5 secondi
    setTimeout(() => {
        if (messageDiv && messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 5000);
}

// Inizializzazione quando il DOM è caricato
document.addEventListener("DOMContentLoaded", function () {
    // Inizializza Quill per il form di aggiunta
    initializeAddQuill();

    // Inizializza SortableJS
    initializeSortable();

    // Gestisci il submit del form di aggiunta
    document
        .getElementById("add-content-form")
        .addEventListener("submit", function (e) {
            const templateType = document.getElementById("template_type").value;
            if (templateType === "testo") {
                // Trasferisci il contenuto HTML di Quill nella textarea nascosta
                const quillContent = quillAddEditor.root.innerHTML;
                document.getElementById("content_testo_hidden").value =
                    quillContent;
            }
        });

    // Gestisci il submit dei form di modifica
    document.querySelectorAll(".edit-content-form").forEach((form) => {
        form.addEventListener("submit", function (e) {
            const contentId = this.getAttribute("data-content-id");
            const templateTypeSelect = this.querySelector(
                'select[name="template_type"]'
            );
            const templateType = templateTypeSelect.value;

            if (templateType === "testo" && quillEditEditors[contentId]) {
                // Trasferisci il contenuto HTML di Quill nella textarea nascosta
                const quillContent = quillEditEditors[contentId].root.innerHTML;
                const hiddenTextarea = document.getElementById(
                    "edit_content_testo_hidden_" + contentId
                );
                if (hiddenTextarea) {
                    hiddenTextarea.value = quillContent;
                }
            }
        });
    });

    // Inizializza i campi visibili
    showFields();
});
