/**
 * JavaScript per la gestione della pagina di creazione serie
 * Dipende dalla configurazione in window.seriesCreateConfig
 */

// Funzione per raccogliere tutti i video selezionati
function getSelectedVideos() {
    const checkboxes = document.querySelectorAll(
        'input[name="videos[]"]:checked'
    );
    return Array.from(checkboxes).map((cb) => cb.value);
}

// Funzione per aggiornare i campi nascosti con le selezioni video
function updateHiddenVideoFields() {
    const container = document.getElementById("hidden_videos_container");
    if (!container) return;

    // Rimuovi tutti i campi nascosti esistenti per i video
    container.innerHTML = "";

    // Aggiungi un campo nascosto per ogni video selezionato
    const selectedVideos = getSelectedVideos();
    selectedVideos.forEach((videoId) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "form_videos[]";
        input.value = videoId;
        container.appendChild(input);
    });
}

// Funzione per resettare solo i filtri mantenendo le modifiche del form e le selezioni
function resetFilters() {
    const nomeInput = document.getElementById("nome_input");
    const descrizioneInput = document.getElementById("descrizione_input");

    // Ottieni la configurazione dal file Blade
    const config = window.seriesCreateConfig;
    if (!config) {
        console.error("Configurazione non trovata");
        return;
    }

    // Costruisci l'URL mantenendo i dati del form e le selezioni video
    const params = new URLSearchParams();

    if (nomeInput && nomeInput.value.trim()) {
        params.append("form_nome", nomeInput.value);
    }
    if (descrizioneInput && descrizioneInput.value.trim()) {
        params.append("form_descrizione", descrizioneInput.value);
    }

    // Aggiungi le selezioni video correnti
    const selectedVideos = getSelectedVideos();
    selectedVideos.forEach((videoId) => {
        params.append("form_videos[]", videoId);
    });

    // Naviga alla pagina senza filtri ma con i dati del form e le selezioni
    const finalUrl = params.toString()
        ? `${config.baseUrl}?${params.toString()}`
        : config.baseUrl;
    window.location.href = finalUrl;
}

// Funzioni JavaScript per gestire le selezioni
function selectAllVideos() {
    const checkboxes = document.querySelectorAll('input[name="videos[]"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = true;
    });
    updateHiddenVideoFields();
}

function deselectAllVideos() {
    const checkboxes = document.querySelectorAll('input[name="videos[]"]');
    checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
    });
    updateHiddenVideoFields();
}

// Inizializzazione quando il DOM è pronto
document.addEventListener("DOMContentLoaded", function () {
    // Verifica che la configurazione sia disponibile
    const config = window.seriesCreateConfig;
    if (!config) {
        console.error(
            "Configurazione series-create non trovata. Assicurati che sia definita prima di questo script."
        );
        return;
    }

    const nomeInput = document.getElementById("nome_input");
    const descrizioneInput = document.getElementById("descrizione_input");
    const filterForm = document.getElementById("filterForm");
    const hiddenNome = document.getElementById("hidden_nome");
    const hiddenDescrizione = document.getElementById("hidden_descrizione");

    // Aggiorna i campi nascosti quando l'utente modifica il form
    function updateHiddenFields() {
        if (nomeInput && hiddenNome) {
            hiddenNome.value = nomeInput.value;
        }
        if (descrizioneInput && hiddenDescrizione) {
            hiddenDescrizione.value = descrizioneInput.value;
        }
        updateHiddenVideoFields();
    }

    // Inizializza i campi nascosti con i valori correnti al caricamento
    if (nomeInput && hiddenNome) {
        hiddenNome.value = nomeInput.value;
        nomeInput.addEventListener("input", updateHiddenFields);
    }

    if (descrizioneInput && hiddenDescrizione) {
        hiddenDescrizione.value = descrizioneInput.value;
        descrizioneInput.addEventListener("input", updateHiddenFields);
    }

    // Inizializza i campi nascosti per i video
    updateHiddenVideoFields();

    // Aggiorna i campi nascosti prima di inviare il form dei filtri
    if (filterForm) {
        filterForm.addEventListener("submit", function (e) {
            updateHiddenFields();
        });
    }

    // Gestione dei checkbox dei video
    const checkboxes = document.querySelectorAll('input[name="videos[]"]');

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", function () {
            // Aggiorna i campi nascosti quando cambia una selezione
            updateHiddenVideoFields();

            // Feedback visivo opzionale per le selezioni
            const listItem = this.closest(".list-group-item");
            if (listItem) {
                if (this.checked) {
                    listItem.classList.add(
                        "border-success",
                        "bg-success-subtle"
                    );
                } else {
                    listItem.classList.remove(
                        "border-success",
                        "bg-success-subtle"
                    );
                }
            }
        });
    });

    // Applica lo stato iniziale ai video già selezionati (se ce ne sono)
    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            const listItem = checkbox.closest(".list-group-item");
            if (listItem) {
                listItem.classList.add("border-success", "bg-success-subtle");
            }
        }
    });
});

// Esporta le funzioni per l'uso globale (se necessario)
window.SeriesCreate = {
    getSelectedVideos,
    updateHiddenVideoFields,
    resetFilters,
    selectAllVideos,
    deselectAllVideos,
};
