document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ JavaScript caricato correttamente!");

    let selectedVideos = new Set(
        document
            .getElementById("selectedVideosInput")
            .value.split(",")
            .map(Number)
            .filter((n) => !isNaN(n) && n > 0)
    );

    console.log("📌 Video iniziali:", [...selectedVideos]);

    function updateHiddenInput() {
        document.getElementById("selectedVideosInput").value = [
            ...selectedVideos,
        ]
            .filter((n) => n > 0)
            .join(",");
    }

    document.querySelectorAll(".add-video").forEach((button) => {
        button.addEventListener("click", function () {
            let videoId = parseInt(this.getAttribute("data-id"), 10);
            if (!isNaN(videoId) && !selectedVideos.has(videoId)) {
                selectedVideos.add(videoId);
                updateHiddenInput();
                moveToSeriesList(this);
            }
        });
    });

    document.querySelectorAll(".remove-video").forEach((button) => {
        button.addEventListener("click", function () {
            let videoId = parseInt(this.getAttribute("data-id"), 10);
            if (!isNaN(videoId) && selectedVideos.has(videoId)) {
                selectedVideos.delete(videoId);
                updateHiddenInput();
                moveToAvailableList(this);
            }
        });
    });

    function moveToSeriesList(button) {
        let listItem = button.closest("li");
        listItem
            .querySelector("button")
            .classList.replace("btn-success", "btn-danger");
        listItem.querySelector("button").textContent = "Rimuovi";
        listItem
            .querySelector("button")
            .classList.replace("add-video", "remove-video");

        document.getElementById("seriesVideos").appendChild(listItem);

        listItem
            .querySelector(".remove-video")
            .addEventListener("click", function () {
                let videoId = parseInt(this.getAttribute("data-id"), 10);
                if (!isNaN(videoId) && selectedVideos.has(videoId)) {
                    selectedVideos.delete(videoId);
                    updateHiddenInput();
                    moveToAvailableList(this);
                }
            });
    }

    function moveToAvailableList(button) {
        let listItem = button.closest("li");
        listItem
            .querySelector("button")
            .classList.replace("btn-danger", "btn-success");
        listItem.querySelector("button").textContent = "Aggiungi";
        listItem
            .querySelector("button")
            .classList.replace("remove-video", "add-video");

        document.getElementById("availableVideos").appendChild(listItem);

        listItem
            .querySelector(".add-video")
            .addEventListener("click", function () {
                let videoId = parseInt(this.getAttribute("data-id"), 10);
                if (!isNaN(videoId) && !selectedVideos.has(videoId)) {
                    selectedVideos.add(videoId);
                    updateHiddenInput();
                    moveToSeriesList(this);
                }
            });
    }
});
