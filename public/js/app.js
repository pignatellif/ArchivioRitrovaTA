document.addEventListener("DOMContentLoaded", function () {
    function getBackgroundColor(element) {
        let bgColor = window.getComputedStyle(element).backgroundColor;

        while (
            bgColor === "rgba(255, 255, 255)" ||
            bgColor === "rgba(0, 0, 0)"
        ) {
            element = element.parentElement;
            if (!element) return "rgb(255, 255, 255)"; // Default: bianco
            bgColor = window.getComputedStyle(element).backgroundColor;
        }
        return bgColor;
    }

    function adjustNavbarColor() {
        let bgColor = getBackgroundColor(document.body);
        let rgb = bgColor.match(/\d+/g);
        if (!rgb) return;

        let brightness = 0.299 * rgb[0] + 0.587 * rgb[1] + 0.114 * rgb[2];

        let navbar = document.querySelector(".navbar");
        let navLinks = document.querySelectorAll(".navbar .nav-link");
        let navbarToggler = document.querySelector(".navbar-toggler-icon");
        let logo = document.querySelector(".navbar-brand img"); // Seleziona il logo

        let newColor = brightness < 128 ? "#fff" : "#000";

        document.documentElement.style.setProperty(
            "--navbar-text-color",
            newColor
        );

        if (brightness < 128) {
            navbar.classList.add("text-light");
            navbar.classList.remove("text-dark");
            if (logo) logo.src = window.logoBianco; // Usa la variabile globale
        } else {
            navbar.classList.add("text-dark");
            navbar.classList.remove("text-light");
            if (logo) logo.src = window.logoNero; // Usa la variabile globale
        }

        navLinks.forEach((link) => {
            link.style.color = newColor;
        });

        if (navbarToggler) {
            navbarToggler.style.filter =
                brightness < 128 ? "invert(1)" : "invert(0)";
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        adjustNavbarColor();
        window.addEventListener("resize", adjustNavbarColor);
    });

    adjustNavbarColor();
    window.addEventListener("resize", adjustNavbarColor);
});

document.addEventListener("DOMContentLoaded", function () {
    function setActiveLink() {
        let currentPath = window.location.pathname;
        let navLinks = document.querySelectorAll(".navbar .nav-link");

        navLinks.forEach((link) => {
            let linkPath = new URL(link.href, window.location.origin).pathname;

            if (currentPath === linkPath || currentPath.startsWith(linkPath)) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    }

    setActiveLink();
});

document.addEventListener("DOMContentLoaded", function () {
    const navbarCollapse = document.querySelector(".navbar-collapse");
    const sections = document.querySelectorAll("section");

    function updateNavbarBackground() {
        let found = false;

        sections.forEach((section) => {
            const rect = section.getBoundingClientRect();
            if (
                rect.top <= window.innerHeight / 2 &&
                rect.bottom >= window.innerHeight / 2
            ) {
                found = true;
                const bgColor =
                    window.getComputedStyle(section).backgroundColor;

                if (
                    bgColor === "rgb(0, 0, 0)" ||
                    bgColor === "rgb(20, 20, 20)"
                ) {
                    navbarCollapse.style.backgroundColor = "white"; // Sfondo chiaro per sezioni scure
                } else {
                    navbarCollapse.style.backgroundColor = "lightgray"; // Sfondo scuro per sezioni chiare
                }
            }
        });

        if (!found) {
            navbarCollapse.style.backgroundColor = "white"; // Valore predefinito
        }
    }

    document
        .querySelector(".navbar-toggler")
        .addEventListener("click", function () {
            updateNavbarBackground();
        });

    window.addEventListener("scroll", updateNavbarBackground);
});
