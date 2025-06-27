document.addEventListener("DOMContentLoaded", () => {
    // Blocca lo scroll inizialmente
    document.body.style.overflow = "hidden";

    // Seleziona il bottone della hero section
    const heroButton = document.querySelector(".hero-button");

    // Aggiungi un evento click al bottone
    if (heroButton) {
        heroButton.addEventListener("click", (event) => {
            event.preventDefault(); // Previene il comportamento predefinito del link

            // Sblocca lo scroll
            document.body.style.overflow = "";

            // Scorri fino alla sezione di benvenuto
            const welcomeSection = document.querySelector("#welcome-section");
            if (welcomeSection) {
                welcomeSection.scrollIntoView({ behavior: "smooth" });
            }
        });
    }

    // Effetto macchina da scrivere
    const texts = [
        "Nel silenzio dei filmini, la voce di chi eravamo.",
        "Ogni bobina è un tesoro da riscoprire.",
        "La memoria rivive attraverso l'obiettivo.",
        "Dove il passato incontra il presente.",
        "Storie dimenticate che tornano a vivere.",
        "Il cinema di famiglia che emoziona ancora.",
    ];

    let textIndex = 0;
    let charIndex = 0;
    const typewriterElement = document.getElementById("typewriter");
    const typingSpeed = 80; // Velocità di scrittura (ms)
    const pauseBetweenTexts = 2500; // Pausa tra un testo e l'altro (ms)
    const deletingSpeed = 40; // Velocità di cancellazione (ms)

    function typeWriter() {
        const currentText = texts[textIndex];

        if (charIndex < currentText.length) {
            // Sta scrivendo
            typewriterElement.textContent = currentText.substring(
                0,
                charIndex + 1
            );
            charIndex++;
            setTimeout(typeWriter, typingSpeed);
        } else {
            // Ha finito di scrivere, pausa e poi cancella
            setTimeout(deleteText, pauseBetweenTexts);
        }
    }

    function deleteText() {
        const currentText = texts[textIndex];

        if (charIndex > 0) {
            // Sta cancellando
            typewriterElement.textContent = currentText.substring(
                0,
                charIndex - 1
            );
            charIndex--;
            setTimeout(deleteText, deletingSpeed);
        } else {
            // Ha finito di cancellare, passa al testo successivo
            textIndex = (textIndex + 1) % texts.length;
            setTimeout(typeWriter, 500); // Piccola pausa prima di iniziare il nuovo testo
        }
    }

    // Inizia l'effetto macchina da scrivere dopo che le animazioni iniziali sono completate
    setTimeout(() => {
        typeWriter();
    }, 2000); // Aspetta che le animazioni iniziali siano finite
});

document.addEventListener("DOMContentLoaded", () => {
    // Animazione progetti uno ad uno
    const progetti = document.querySelectorAll(".progetto");
    const progettiGrid = document.querySelector(".griglia-progetti");
    if (progetti.length > 0) {
        const observer = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        progetti.forEach((proj, i) => {
                            setTimeout(() => {
                                proj.classList.add("show-project");
                            }, i * 200); // Delay progressivo
                        });
                        observer.unobserve(progettiGrid);
                    }
                });
            },
            { threshold: 0.2 }
        );

        observer.observe(progettiGrid);
    }

    // Archive sections animazioni
    const archiveSections = document.querySelectorAll(".archive-section");
    archiveSections.forEach((section) => {
        const imageHalf = section.querySelector(".archive-half.image");
        const textHalf = section.querySelector(".archive-half.text");
        section.classList.add("archive-init");

        const obs = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        section.classList.add("archive-animate");
                        obs.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.3 }
        );
        obs.observe(section);
    });
});
