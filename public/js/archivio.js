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
    const resetButton = document.getElementById("resetFilters");
    const gridViewBtn = document.getElementById("gridViewBtn");
    const mapViewBtn = document.getElementById("mapViewBtn");
    const loadingIndicator = document.getElementById("loadingIndicator");
    const videoContainer = document.getElementById("videoContainer");

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

    function updateFilters(view = "grid") {
        if (parseInt(yearSlider1.value) > parseInt(yearSlider2.value)) {
            yearSlider2.value = yearSlider1.value;
        }
        if (parseInt(durationSlider1.value) > parseInt(durationSlider2.value)) {
            durationSlider2.value = durationSlider1.value;
        }

        const filters = {
            title: filterTitle.value.trim(),
            author: filterAuthor.value.trim(),
            format: filterFormat.value.trim(),
            family: filterFamily.value.trim(),
            location: filterLocation.value.trim(),
            min_year: yearSlider1.value,
            max_year: yearSlider2.value,
            min_duration: durationSlider1.value,
            max_duration: durationSlider2.value,
            view: view,
        };

        fetchFilters(filters);
    }

    function fetchFilters(filters) {
        const queryString = new URLSearchParams(filters).toString();
        loadingIndicator.style.display = "block";

        fetch(`/archivio?${queryString}`, {
            // 👈 nuovo endpoint
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.json())
            .then((data) => {
                if (videoContainer) {
                    videoContainer.innerHTML = data.html;

                    if (filters.view === "map") {
                        videoContainer.style.display = "block";
                        const mapElement = videoContainer.querySelector("#map");
                        if (mapElement) {
                            const locations = JSON.parse(
                                mapElement.dataset.locations || "[]"
                            );
                            console.log("🗺️ Luoghi ricevuti:", locations);
                            initLeafletMap(locations);
                        }
                    } else {
                        videoContainer.style.display = "grid";
                    }
                }
            })
            .catch((error) => console.error("Errore caricamento:", error))
            .finally(() => {
                loadingIndicator.style.display = "none";
            });
    }

    function fetchVideosByLocation(locationName) {
        const filters = {
            title: "",
            author: "",
            format: "",
            family: "",
            location: locationName,
            min_year: yearSlider1.min,
            max_year: yearSlider2.max,
            min_duration: durationSlider1.min,
            max_duration: durationSlider2.max,
            view: "grid",
        };

        loadingIndicator.style.display = "block";

        fetch(`/archivio?${new URLSearchParams(filters).toString()}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.json())
            .then((data) => {
                if (videoContainer) {
                    videoContainer.innerHTML = data.html;
                    videoContainer.style.display = "grid";
                }
            })
            .catch((error) => console.error("Errore caricamento luogo:", error))
            .finally(() => {
                loadingIndicator.style.display = "none";
            });
    }

    function resetFilters() {
        filterTitle.value = "";
        filterAuthor.value = "";
        filterFormat.value = "";
        filterFamily.value = "";
        filterLocation.value = "";

        document.querySelectorAll(".btn-clear-input").forEach((button) => {
            button.classList.add("d-none");
        });

        yearSlider1.value = yearSlider1.min;
        yearSlider2.value = yearSlider2.max;
        durationSlider1.value = durationSlider1.min;
        durationSlider2.value = durationSlider2.max;

        updateSliderLabels();

        const view = gridViewBtn.classList.contains("active") ? "grid" : "map";
        updateFilters(view);
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

    async function geocodeQueue(locations, map, delay = 1100) {
        for (const locationName of locations) {
            if (
                typeof locationName === "string" &&
                locationName.trim() !== ""
            ) {
                try {
                    const response = await fetch(
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
                            locationName
                        )}`,
                        {
                            headers: {
                                "Accept-Language": "it",
                                "User-Agent": "ArchivioApp (info@tuosito.it)", // 👈 Personalizza
                            },
                        }
                    );

                    const data = await response.json();
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lon = parseFloat(data[0].lon);
                        L.marker([lat, lon])
                            .addTo(map)
                            .bindPopup(locationName)
                            .on("click", () => {
                                fetchVideosByLocation(locationName);
                            });
                    } else {
                        console.warn(`Nessun risultato per: ${locationName}`);
                    }
                } catch (err) {
                    console.error(`Errore geocoding per ${locationName}:`, err);
                }

                await new Promise((resolve) => setTimeout(resolve, delay));
            }
        }
    }

    function initLeafletMap(locations) {
        const map = L.map("map", { scrollWheelZoom: false }).setView(
            [41.8719, 12.5674],
            6
        );

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap contributors",
        }).addTo(map);

        geocodeQueue(locations, map);
    }

    gridViewBtn.addEventListener("click", function () {
        gridViewBtn.classList.add("active");
        mapViewBtn.classList.remove("active");
        updateFilters("grid");
    });

    mapViewBtn.addEventListener("click", function () {
        mapViewBtn.classList.add("active");
        gridViewBtn.classList.remove("active");
        updateFilters("map");
    });

    // Caricamento iniziale
    updateFilters("grid");
});
