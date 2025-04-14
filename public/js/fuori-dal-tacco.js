document.addEventListener("DOMContentLoaded", function () {
    let timeout = null;

    // Selettori
    const yearSlider1 = document.getElementById("yearSlider1");
    const yearSlider2 = document.getElementById("yearSlider2");
    const durationSlider1 = document.getElementById("durationSlider1");
    const durationSlider2 = document.getElementById("durationSlider2");
    const filterTitle = document.getElementById("filterTitle");
    const filterAuthor = document.getElementById("filterAuthor");
    const resetButton = document.getElementById("resetFilters");
    const gridViewBtn = document.getElementById("gridViewBtn");
    const mapViewBtn = document.getElementById("mapViewBtn");
    const loadingIndicator = document.getElementById("loadingIndicator");

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
        input.addEventListener("input", debounceFilters);
    });

    function initLeafletMap(locations) {
        const map = L.map("map").setView([41.8719, 12.5674], 6);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        locations.forEach((location) => {
            if (location) {
                const [lat, lng] = location
                    .split(",")
                    .map((coord) => parseFloat(coord.trim()));
                if (!isNaN(lat) && !isNaN(lng)) {
                    L.marker([lat, lng]).addTo(map);
                }
            }
        });
    }

    function updateFilters(view = "grid") {
        if (parseInt(yearSlider1.value) > parseInt(yearSlider2.value)) {
            yearSlider2.value = yearSlider1.value;
        }
        if (parseInt(durationSlider1.value) > parseInt(durationSlider2.value)) {
            durationSlider2.value = durationSlider1.value;
        }

        const filterData = {
            title: filterTitle.value.trim(),
            author: filterAuthor.value.trim(),
            min_year: yearSlider1.value,
            max_year: yearSlider2.value,
            min_duration: durationSlider1.value,
            max_duration: durationSlider2.value,
        };

        fetchFilters(filterData, view);
    }

    function fetchFilters(filters, view = "grid") {
        filters.view = view;

        const queryString = new URLSearchParams(filters).toString();
        loadingIndicator.style.display = "block";
        fetch(`/fuori-dal-tacco?${queryString}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.json())
            .then((data) => {
                const container = document.getElementById("videoContainer");
                if (container) {
                    container.innerHTML = data.html;

                    if (view === "map") {
                        container.style.display = "block";
                        const mapElement = container.querySelector("#map");
                        if (mapElement) {
                            const locations = JSON.parse(
                                mapElement.dataset.locations || "[]"
                            );
                            initLeafletMap(locations);
                        }
                    } else {
                        container.style.display = "grid";
                    }
                } else {
                    console.error("Elemento #videoContainer non trovato!");
                }
            })
            .catch((error) =>
                console.error("Errore nel caricamento dei video:", error)
            )
            .finally(() => {
                loadingIndicator.style.display = "none";
            });
    }

    function resetFilters() {
        filterTitle.value = "";
        filterAuthor.value = "";

        document.querySelectorAll(".btn-clear-input").forEach((button) => {
            button.classList.add("d-none");
        });

        yearSlider1.value = yearSlider1.min;
        yearSlider2.value = yearSlider2.max;
        durationSlider1.value = durationSlider1.min;
        durationSlider2.value = durationSlider2.max;

        updateSliderLabels();

        const currentView = gridViewBtn.classList.contains("active")
            ? "grid"
            : "map";

        const defaultFilterData = {
            title: "",
            author: "",
            min_year: yearSlider1.min,
            max_year: yearSlider2.max,
            min_duration: durationSlider1.min,
            max_duration: durationSlider2.max,
        };
        fetchFilters(defaultFilterData, currentView);
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

    [filterTitle, filterAuthor].forEach((input) => {
        input.addEventListener("input", () => {
            toggleClearButton(input);
            debounceFilters();
        });
        toggleClearButton(input);
    });

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

    const defaultFilterData = {
        title: "",
        author: "",
        min_year: yearSlider1.min,
        max_year: yearSlider2.max,
        min_duration: durationSlider1.min,
        max_duration: durationSlider2.max,
    };
    fetchFilters(defaultFilterData, "grid");
});
