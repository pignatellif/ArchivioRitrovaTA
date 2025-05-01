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
});
