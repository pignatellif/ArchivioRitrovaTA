document.addEventListener("DOMContentLoaded", () => {
    const images = [
        "/img/home/image1.png",
        "/img/home/image2.png",
        "/img/home/image3.png",
        "/img/home/image4.png",
    ]; // Percorsi delle immagini

    let currentIndex = 0;
    const bgCurrent = document.querySelector(".background-slider");
    const bgNext = document.querySelector(".background-slider-next");
    const ctaButton = document.querySelector(".cta-button");
    const sections = document.querySelectorAll(".section");

    const changeBackground = () => {
        bgNext.style.backgroundImage = `url('${images[currentIndex]}')`;
        bgNext.style.opacity = 1;

        setTimeout(() => {
            bgCurrent.style.backgroundImage = `url('${images[currentIndex]}')`;
            bgNext.style.opacity = 0;
            currentIndex = (currentIndex + 1) % images.length;
        }, 2000);
    };

    setInterval(changeBackground, 5000);
    changeBackground();

    ctaButton.addEventListener("click", () => {
        document.body.style.overflow = "auto"; // Sblocca lo scroll
        document.documentElement.style.scrollBehavior = "smooth"; // Effetto scroll morbido
    });

    const revealSections = () => {
        sections.forEach((section) => {
            const sectionTop = section.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (sectionTop < windowHeight - 100) {
                section.classList.add("show");
            }
        });
    };

    window.addEventListener("scroll", revealSections);
});
