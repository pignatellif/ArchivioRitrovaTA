function scrollCarousel(index, direction) {
    const carousel = document.getElementById(`carousel-${index}`);
    const scrollAmount = carousel.clientWidth * 0.8;
    carousel.scrollBy({
        left: direction * scrollAmount,
        behavior: "smooth",
    });
}

// Disabilita lo scroll orizzontale nativo
document.querySelectorAll('[id^="carousel-"]').forEach((carousel) => {
    carousel.style.overflowX = "hidden";
});
