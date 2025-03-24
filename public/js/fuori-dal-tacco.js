document.addEventListener("DOMContentLoaded", function () {
    let timeout = null;

    // Selettori per gli elementi dei filtri
    const yearSlider1 = document.getElementById("yearSlider1");
    const yearSlider2 = document.getElementById("yearSlider2");
    const durationSlider1 = document.getElementById("durationSlider1");
    const durationSlider2 = document.getElementById("durationSlider2");
    const filterTitle = document.getElementById("filterTitle");
    const filterAuthor = document.getElementById("filterAuthor");

    // Aggiorna i valori dei label immediatamente
    function updateSliderLabels() {
        document.getElementById("yearValue1").textContent = yearSlider1.value;
        document.getElementById("yearValue2").textContent = yearSlider2.value;
        document.getElementById("durationValue1").textContent =
            durationSlider1.value;
        document.getElementById("durationValue2").textContent =
            durationSlider2.value;
    }

    // Invia i filtri dopo 500ms di inattività
    function debounceFilters() {
        clearTimeout(timeout);
        timeout = setTimeout(updateFilters, 100);
    }

    // Evento immediato per aggiornare i label degli slider
    [yearSlider1, yearSlider2, durationSlider1, durationSlider2].forEach(
        (slider) => {
            slider.addEventListener("input", updateSliderLabels);
            slider.addEventListener("change", debounceFilters); // Solo al rilascio avvia il debounce
        }
    );

    // Evento di input con debounce per i campi di testo
    [filterTitle, filterAuthor].forEach((input) => {
        input.addEventListener("input", debounceFilters);
    });

    // Funzione per aggiornare i filtri
    function updateFilters() {
        // Corregge eventuali inversioni nei range degli slider
        if (parseInt(yearSlider1.value) > parseInt(yearSlider2.value)) {
            yearSlider2.value = yearSlider1.value;
        }
        if (parseInt(durationSlider1.value) > parseInt(durationSlider2.value)) {
            durationSlider2.value = durationSlider1.value;
        }

        // Crea l'oggetto con i parametri di filtro
        const filterData = {
            title: filterTitle.value.trim(),
            author: filterAuthor.value.trim(),
            min_year: yearSlider1.value,
            max_year: yearSlider2.value,
            min_duration: durationSlider1.value,
            max_duration: durationSlider2.value,
        };

        // Chiamata AJAX per aggiornare i risultati
        fetchFilters(filterData);
    }

    // Funzione per effettuare la richiesta AJAX
    function fetchFilters(filters) {
        const queryString = new URLSearchParams(filters).toString();
        fetch(`/fuori-dal-tacco?${queryString}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.json())
            .then((data) => {
                const container = document.getElementById("videoContainer");
                if (container) {
                    container.innerHTML = data.html;
                } else {
                    console.error("Elemento #videoContainer non trovato!");
                }
            })
            .catch((error) =>
                console.error("Errore nel caricamento dei video:", error)
            );
    }
});

document.addEventListener("DOMContentLoaded", async function () {
    var locationsElement = document.getElementById("locations-data");
    if (!locationsElement) {
        console.error("Element with ID 'locations-data' not found.");
        return;
    }

    var locations = JSON.parse(locationsElement.textContent); // Passa le locations dal PHP
    var map = L.map("map", {
        scrollWheelZoom: false, // Disabilita lo zoom con lo scroll
    }).setView([41.8719, 12.5674], 5); // Centro sull'Italia

    // Aggiunge il layer della mappa (OpenStreetMap)
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    // Funzione per ottenere le coordinate da OpenStreetMap
    async function getCoordinates(location) {
        try {
            // Aggiungi contesto alla query per migliorare la precisione
            let query = location;
            if (location.toLowerCase() === "san marino") {
                query = "San Marino";
            } else if (location.toLowerCase() === "valle d'aosta") {
                query = "Valle d'Aosta, Italia";
            } else {
                query += ", Italia";
            }

            let response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
                    query
                )}`
            );
            let data = await response.json();
            console.log(`Risultati per ${location}:`, data); // Logga i risultati per il debug
            if (data.length > 0) {
                return {
                    lat: parseFloat(data[0].lat),
                    lon: parseFloat(data[0].lon),
                };
            } else {
                console.warn("Coordinate non trovate per:", location);
                return null;
            }
        } catch (error) {
            console.error("Errore nel recupero delle coordinate:", error);
            return null;
        }
    }

    // Ciclo sulle location per ottenere le coordinate e aggiungere i marker
    for (const location of locations) {
        let coords = await getCoordinates(location);
        if (coords) {
            L.marker([coords.lat, coords.lon])
                .addTo(map)
                .bindPopup(`<b>${location}</b>`);
        }
    }
});
