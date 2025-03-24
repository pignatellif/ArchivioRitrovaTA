document.querySelectorAll(".carousel").forEach((carousel) => {
    const videoList = carousel.querySelector(".video-list");
    const leftArrow = carousel.querySelector(".left-arrow");
    const rightArrow = carousel.querySelector(".right-arrow");
    const scrollAmount = videoList.querySelector(".item").offsetWidth + 20; // Spazio tra i video

    leftArrow.addEventListener("click", () => {
        videoList.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });

    rightArrow.addEventListener("click", () => {
        videoList.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });
});
