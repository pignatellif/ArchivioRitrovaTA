/**
 * Video Show Page JavaScript
 * Handles YouTube player initialization and carousel functionality
 */

class VideoShow {
    constructor() {
        this.player = null;
        this.init();
    }

    init() {
        this.initYouTubePlayer();
        this.initCarousel();
        this.initKeyboardNavigation();
    }

    /**
     * Initialize YouTube Player with API
     */
    initYouTubePlayer() {
        const iframe = document.getElementById("youtube-iframe");
        if (!iframe) return;

        // Update iframe src with API parameters
        const originalSrc = iframe.getAttribute("src");
        const separator = originalSrc.includes("?") ? "&" : "?";
        const newSrc = `${originalSrc}${separator}enablejsapi=1&autoplay=1&mute=1`;
        iframe.setAttribute("src", newSrc);

        // Load YouTube API
        this.loadYouTubeAPI();
    }

    /**
     * Load YouTube IFrame API
     */
    loadYouTubeAPI() {
        // Check if API is already loaded
        if (window.YT && window.YT.Player) {
            this.createPlayer();
            return;
        }

        // Load API script
        const tag = document.createElement("script");
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName("script")[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // Set up API ready callback
        window.onYouTubeIframeAPIReady = () => {
            this.createPlayer();
        };
    }

    /**
     * Create YouTube player instance
     */
    createPlayer() {
        this.player = new YT.Player("youtube-iframe", {
            events: {
                onReady: (event) => {
                    event.target.mute();
                    event.target.playVideo();
                },
                onStateChange: (event) => {
                    this.handlePlayerStateChange(event);
                },
            },
        });
    }

    /**
     * Handle player state changes
     */
    handlePlayerStateChange(event) {
        // Optional: Add analytics or other functionality here
        if (event.data === YT.PlayerState.PLAYING) {
            console.log("Video started playing");
        }
    }

    /**
     * Initialize carousel functionality
     */
    initCarousel() {
        const carouselButtons = document.querySelectorAll(".carousel-btn");

        carouselButtons.forEach((button) => {
            button.addEventListener("click", (e) => {
                e.preventDefault();
                this.scrollCarousel(button);
            });
        });

        // Add touch/swipe support for mobile
        this.initTouchSupport();
    }

    /**
     * Scroll carousel in specified direction
     */
    scrollCarousel(button) {
        const carouselId = button.getAttribute("data-carousel");
        const direction = parseInt(button.getAttribute("data-direction"));
        const carousel = document.getElementById(`carousel-${carouselId}`);

        if (!carousel) return;

        const scrollAmount = this.calculateScrollAmount(carousel) * direction;

        carousel.scrollBy({
            left: scrollAmount,
            behavior: "smooth",
        });

        // Update button states
        this.updateCarouselButtons(carousel, carouselId);
    }

    /**
     * Calculate optimal scroll amount based on carousel width
     */
    calculateScrollAmount(carousel) {
        const carouselWidth = carousel.offsetWidth;
        const itemWidth =
            carousel.querySelector(".carousel-item")?.offsetWidth || 280;
        const visibleItems = Math.floor(carouselWidth / itemWidth);

        return Math.max(itemWidth * Math.max(1, visibleItems - 1), 250);
    }

    /**
     * Update carousel button states based on scroll position
     */
    updateCarouselButtons(carousel, carouselId) {
        const prevBtn = document.querySelector(
            `.carousel-btn--prev[data-carousel="${carouselId}"]`
        );
        const nextBtn = document.querySelector(
            `.carousel-btn--next[data-carousel="${carouselId}"]`
        );

        if (!prevBtn || !nextBtn) return;

        const isAtStart = carousel.scrollLeft <= 0;
        const isAtEnd =
            carousel.scrollLeft >=
            carousel.scrollWidth - carousel.offsetWidth - 10;

        prevBtn.style.opacity = isAtStart ? "0.5" : "1";
        nextBtn.style.opacity = isAtEnd ? "0.5" : "1";

        prevBtn.disabled = isAtStart;
        nextBtn.disabled = isAtEnd;
    }

    /**
     * Initialize touch/swipe support for mobile
     */
    initTouchSupport() {
        const carousels = document.querySelectorAll(".carousel");

        carousels.forEach((carousel) => {
            let isDown = false;
            let startX;
            let scrollLeft;

            carousel.addEventListener("mousedown", (e) => {
                isDown = true;
                carousel.classList.add("active");
                startX = e.pageX - carousel.offsetLeft;
                scrollLeft = carousel.scrollLeft;
            });

            carousel.addEventListener("mouseleave", () => {
                isDown = false;
                carousel.classList.remove("active");
            });

            carousel.addEventListener("mouseup", () => {
                isDown = false;
                carousel.classList.remove("active");
            });

            carousel.addEventListener("mousemove", (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - carousel.offsetLeft;
                const walk = (x - startX) * 2;
                carousel.scrollLeft = scrollLeft - walk;
            });

            // Touch events
            carousel.addEventListener("touchstart", (e) => {
                startX = e.touches[0].pageX - carousel.offsetLeft;
                scrollLeft = carousel.scrollLeft;
            });

            carousel.addEventListener("touchmove", (e) => {
                if (!startX) return;
                const x = e.touches[0].pageX - carousel.offsetLeft;
                const walk = (x - startX) * 2;
                carousel.scrollLeft = scrollLeft - walk;
            });
        });
    }

    /**
     * Initialize keyboard navigation
     */
    initKeyboardNavigation() {
        document.addEventListener("keydown", (e) => {
            // Space bar to pause/play video
            if (e.code === "Space" && this.player && !this.isInputFocused()) {
                e.preventDefault();
                const state = this.player.getPlayerState();
                if (state === YT.PlayerState.PLAYING) {
                    this.player.pauseVideo();
                } else {
                    this.player.playVideo();
                }
            }

            // Arrow keys for carousel navigation
            if (e.code === "ArrowLeft" || e.code === "ArrowRight") {
                const focusedCarousel = document.querySelector(
                    ".carousel:focus-within"
                );
                if (focusedCarousel) {
                    e.preventDefault();
                    const direction = e.code === "ArrowLeft" ? -1 : 1;
                    const scrollAmount =
                        this.calculateScrollAmount(focusedCarousel) * direction;
                    focusedCarousel.scrollBy({
                        left: scrollAmount,
                        behavior: "smooth",
                    });
                }
            }
        });
    }

    /**
     * Check if an input element is currently focused
     */
    isInputFocused() {
        const activeElement = document.activeElement;
        return (
            activeElement &&
            (activeElement.tagName === "INPUT" ||
                activeElement.tagName === "TEXTAREA" ||
                activeElement.contentEditable === "true")
        );
    }

    /**
     * Cleanup method
     */
    destroy() {
        if (this.player) {
            this.player.destroy();
        }
    }
}

/**
 * Intersection Observer for lazy loading improvements
 */
class LazyImageLoader {
    constructor() {
        this.init();
    }

    init() {
        if ("IntersectionObserver" in window) {
            this.createObserver();
        }
    }

    createObserver() {
        const options = {
            root: null,
            rootMargin: "50px",
            threshold: 0.1,
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    this.loadImage(entry.target);
                    this.observer.unobserve(entry.target);
                }
            });
        }, options);

        // Observe carousel images
        const carouselImages = document.querySelectorAll(".carousel img");
        carouselImages.forEach((img) => {
            this.observer.observe(img);
        });
    }

    loadImage(img) {
        if (img.dataset.src) {
            img.src = img.dataset.src;
            img.classList.add("loaded");
        }
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
    const videoShow = new VideoShow();
    const lazyLoader = new LazyImageLoader();

    // Cleanup on page unload
    window.addEventListener("beforeunload", () => {
        videoShow.destroy();
    });
});

// Export for potential external use
window.VideoShow = VideoShow;
