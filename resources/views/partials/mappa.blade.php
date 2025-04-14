<div id="map" style="height: 80vh; width: 100%;"></div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('map').setView([41.8719, 12.5674], 6); // Italia come posizione centrale

        // Aggiungi il layer di base da OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Lista delle coordinate ricevute dal controller
        const locations = @json($locationList);

        // Aggiungi un marker per ogni location
        locations.forEach(function(location) {
            if (location.lat !== undefined && location.lng !== undefined) {
                L.marker([location.lat, location.lng]).addTo(map);
            }
        });
    });
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
@endpush