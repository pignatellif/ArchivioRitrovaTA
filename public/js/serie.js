function scrollCarousel(index, direction) {
    const carousel = document.getElementById(`carousel-${index}`);
    const scrollAmount = carousel.clientWidth * 0.8;
    carousel.scrollBy({
        left: direction * scrollAmount,
        behavior: "smooth",
    });
}
