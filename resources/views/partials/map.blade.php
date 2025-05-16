<div class="map-container">
    <div id="map" data-locations='@json($locations->filter(fn($loc) => $loc && $loc->lat && $loc->lon)->values())'></div>
</div>

<div class="section-divider"></div>

<div id="videoResultsByLocation" class="video-results">

</div>

