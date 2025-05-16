document.addEventListener("DOMContentLoaded", function () {
    let timeout = null;

    // Selettori
    const yearSlider1 = document.getElementById("yearSlider1");
    const yearSlider2 = document.getElementById("yearSlider2");
    const durationSlider1 = document.getElementById("durationSlider1");
    const durationSlider2 = document.getElementById("durationSlider2");
    const filterTitle = document.getElementById("filterTitle");
    const filterAuthor = document.getElementById("filterAuthor");
    const filterFormat = document.getElementById("filterFormat");
    const filterFamily = document.getElementById("filterFamily");
    const filterLocation = document.getElementById("filterLocation");
    const filterTags = document.getElementById("filterTags");
    const resetButton = document.getElementById("resetFilters");
    const viewGridInput = document.getElementById("viewGrid");
    const viewMapInput = document.getElementById("viewMap");
    const dynamicViewSection = document.getElementById("dynamicViewSection");

    function updateSliderLabels() {
        document.getElementById("yearValue1").textContent = yearSlider1.value;
        document.getElementById("yearValue2").textContent = yearSlider2.value;
        document.getElementById("durationValue1").textContent =
            durationSlider1.value;
        document.getElementById("durationValue2").textContent =
            durationSlider2.value;
    }

    function debounceFilters() {
        clearTimeout(timeout);
        timeout = setTimeout(updateFilters, 100);
    }

    [yearSlider1, yearSlider2, durationSlider1, durationSlider2].forEach(
        (slider) => {
            slider.addEventListener("input", updateSliderLabels);
            slider.addEventListener("change", debounceFilters);
        }
    );

    [filterTitle, filterAuthor].forEach((input) => {
        input.addEventListener("input", () => {
            toggleClearButton(input);
            debounceFilters();
        });
        toggleClearButton(input);
    });

    [filterFormat, filterFamily, filterLocation].forEach((select) => {
        select.addEventListener("change", debounceFilters);
    });

    document.querySelectorAll(".filter-tag").forEach((checkbox) => {
        checkbox.addEventListener("change", debounceFilters);
    });

    function collectFilters(overrides = {}) {
        // Raccogli tutti i tag selezionati come array
        const selectedTags = Array.from(
            document.querySelectorAll(".filter-tag:checked")
        ).map((checkbox) => checkbox.value);

        return {
            title: filterTitle.value.trim(),
            author: filterAuthor.value.trim(),
            format: filterFormat.value.trim(),
            family: filterFamily.value.trim(),
            location: filterLocation.value.trim(),
            tags: selectedTags,
            min_year: yearSlider1.value,
            max_year: yearSlider2.value,
            min_duration: durationSlider1.value,
            max_duration: durationSlider2.value,
            view: viewGridInput.checked ? "grid" : "map",
            ...overrides,
        };
    }

    function updateFilters(overrides = {}) {
        if (parseInt(yearSlider1.value) > parseInt(yearSlider2.value)) {
            yearSlider2.value = yearSlider1.value;
        }
        if (parseInt(durationSlider1.value) > parseInt(durationSlider2.value)) {
            durationSlider2.value = durationSlider1.value;
        }

        const filters = collectFilters(overrides);
        fetchFilters(filters);
    }

    function fetchFilters(filters) {
        const params = new URLSearchParams();

        Object.entries(filters).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach((v) => params.append(`${key}[]`, v));
            } else {
                params.append(key, value);
            }
        });

        fetch(`/fuori-dal-tacco?${params.toString()}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.json())
            .then((data) => {
                dynamicViewSection.innerHTML = data.html;

                if (filters.view === "map") {
                    const mapElement = dynamicViewSection.querySelector("#map");
                    if (mapElement) {
                        const locations = JSON.parse(
                            mapElement.dataset.locations || "[]"
                        );
                        console.log("Mappa: location ricevute", locations);
                        console.log(mapElement.dataset.locations);
                        initLeafletMap(locations);
                    }
                }

                attachPaginationHandlers(); // <- Attiva i link di paginazione AJAX
            })
            .catch((error) => console.error("Errore caricamento:", error));
    }

    function attachPaginationHandlers() {
        const paginationLinks =
            dynamicViewSection.querySelectorAll(".pagination a");

        paginationLinks.forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const url = new URL(link.href);
                const page = url.searchParams.get("page");
                updateFilters({ page });
            });
        });
    }

    function resetFilters() {
        filterTitle.value = "";
        filterAuthor.value = "";
        filterFormat.value = "";
        filterFamily.value = "";
        filterLocation.value = "";

        document
            .querySelectorAll(".filter-tag")
            .forEach((checkbox) => (checkbox.checked = false));

        document.querySelectorAll(".btn-clear-input").forEach((button) => {
            button.classList.add("d-none");
        });

        yearSlider1.value = yearSlider1.min;
        yearSlider2.value = yearSlider2.max;
        durationSlider1.value = durationSlider1.min;
        durationSlider2.value = durationSlider2.max;

        updateSliderLabels();
        updateFilters();
    }

    resetButton.addEventListener("click", resetFilters);

    document.querySelectorAll(".btn-clear-input").forEach((button) => {
        const targetInput = document.querySelector(button.dataset.target);
        if (targetInput) {
            button.addEventListener("click", function () {
                targetInput.value = "";
                toggleClearButton(targetInput);
                targetInput.dispatchEvent(new Event("input"));
            });
        }
    });

    function toggleClearButton(input) {
        const button = document.querySelector(
            `.btn-clear-input[data-target="#${input.id}"]`
        );
        if (button) {
            if (input.value.trim() !== "") {
                button.classList.remove("d-none");
            } else {
                button.classList.add("d-none");
            }
        }
    }

    let leafletMap = null;

    function initLeafletMap(locations) {
        const map = L.map("map", { scrollWheelZoom: false }).setView(
            [41.8719, 12.5674],
            6
        );

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap contributors",
        }).addTo(map);

        locations.forEach((location) => {
            if (location.name && location.lat && location.lon) {
                L.marker([location.lat, location.lon])
                    .addTo(map)
                    .bindPopup(location.name)
                    .on("click", () => {
                        filterLocation.value = location.name; // aggiorna il filtro
                        loadVideosByLocation(location.name); // carica i video
                    });
            }
        });
    }

    function loadVideosByLocation(locationName) {
        const filters = collectFilters({
            location: locationName,
            view: "grid",
        });

        const params = new URLSearchParams();
        Object.entries(filters).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach((v) => params.append(`${key}[]`, v));
            } else {
                params.append(key, value);
            }
        });

        fetch(`/fuori-dal-tacco?${params.toString()}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Risposta ricevuta:", data); // aggiungi questo

                const resultsContainer = document.getElementById(
                    "videoResultsByLocation"
                );
                if (resultsContainer) {
                    resultsContainer.innerHTML = data.html;
                    resultsContainer.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                }
            })
            .catch((error) =>
                console.error("Errore nel caricamento dei video:", error)
            );
    }

    [viewGridInput, viewMapInput].forEach((input) => {
        input.addEventListener("change", function () {
            resetFilters(); // <-- Resetta i filtri prima di applicare la nuova view
        });
    });

    updateFilters(); // caricamento iniziale
});

document.getElementById("toggleFilters").addEventListener("click", function () {
    const sidebarElement = document.getElementById("filterSidebar");
    const bsOffcanvas = new bootstrap.Offcanvas(sidebarElement);
    bsOffcanvas.show();
});

document
    .getElementById("toggleTagFilter")
    .addEventListener("click", function () {
        const tagList = document.getElementById("tagList");
        tagList.classList.toggle("d-none");
        this.textContent = tagList.classList.contains("d-none")
            ? "Mostra"
            : "Nascondi";
    });
