document.addEventListener("DOMContentLoaded", async function () {
    var locationsElement = document.getElementById("locations-data");
    if (!locationsElement) {
        console.error("Element with ID 'locations-data' not found.");
        return;
    }

    var locations = JSON.parse(locationsElement.textContent); // Passa le locations dal PHP
    var map = L.map("map", {
        scrollWheelZoom: false, // Disabilita lo zoom con lo scroll
    }).setView([41.1258, 16.8623], 7); // Centro sulla Puglia

    // Aggiunge il layer della mappa (OpenStreetMap)
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "&copy; OpenStreetMap contributors",
    }).addTo(map);

    // Funzione per ottenere le coordinate da OpenStreetMap
    async function getCoordinates(location) {
        try {
            let response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(
                    location + ", Puglia, Italia"
                )}`
            );
            let data = await response.json();
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
