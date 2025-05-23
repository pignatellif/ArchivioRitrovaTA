document.addEventListener("DOMContentLoaded", function () {
    let timeout = null;

    // Selettori attuali secondo la nuova blade
    const durationSlider1 = document.getElementById("durationSlider1");
    const durationSlider2 = document.getElementById("durationSlider2");
    const filterTitle = document.getElementById("filterTitle");
    const filterAuthor = document.getElementById("filterAuthor");
    const filterFamily = document.getElementById("filterFamily");
    const filterLocation = document.getElementById("filterLocation");
    const resetButton = document.getElementById("resetFilters");
    const dynamicViewSection = document.getElementById("dynamicViewSection");

    function updateSliderLabels() {
        document.getElementById("durationValue1").textContent =
            durationSlider1.value;
        document.getElementById("durationValue2").textContent =
            durationSlider2.value;
    }

    function debounceFilters() {
        clearTimeout(timeout);
        timeout = setTimeout(updateFilters, 100);
    }

    [durationSlider1, durationSlider2].forEach((slider) => {
        slider.addEventListener("input", updateSliderLabels);
        slider.addEventListener("change", debounceFilters);
    });

    [filterTitle, filterAuthor].forEach((input) => {
        input.addEventListener("input", () => {
            toggleClearButton(input);
            debounceFilters();
        });
        toggleClearButton(input);
    });

    [filterFamily, filterLocation].forEach((select) => {
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
            family: filterFamily.value.trim(),
            location: filterLocation.value.trim(),
            tags: selectedTags,
            min_duration: durationSlider1.value,
            max_duration: durationSlider2.value,
            ...overrides,
        };
    }

    function updateFilters(overrides = {}) {
        if (parseInt(durationSlider1.value) > parseInt(durationSlider2.value)) {
            durationSlider2.value = durationSlider1.value;
        }

        const filters = collectFilters(overrides);
        fetchFilters(filters);
    }

    function updateFilters(overrides = {}) {
        if (parseInt(durationSlider1.value) > parseInt(durationSlider2.value)) {
            durationSlider2.value = durationSlider1.value;
        }

        const filters = collectFilters(overrides);
        const params = new URLSearchParams();

        Object.entries(filters).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach((v) => params.append(`${key}[]`, v));
            } else {
                if (value !== "") params.append(key, value);
            }
        });

        fetch(`/fuori-dal-frame/personaggi?${params.toString()}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then((response) => response.json())
            .then((data) => {
                dynamicViewSection.innerHTML = data.html;
                attachPaginationHandlers();
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
        filterFamily.value = "";
        filterLocation.value = "";

        document
            .querySelectorAll(".filter-tag")
            .forEach((checkbox) => (checkbox.checked = false));

        document.querySelectorAll(".btn-clear-input").forEach((button) => {
            button.classList.add("d-none");
        });

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

    // Caricamento iniziale
    updateFilters();
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
