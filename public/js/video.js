// Enhanced Video Player JavaScript
document.addEventListener("DOMContentLoaded", function () {
    // ========== ENHANCED CAROUSEL FUNCTIONALITY ==========
    function initEnhancedCarousel() {
        const carousels = document.querySelectorAll(".carousel");

        carousels.forEach((carousel) => {
            const wrapper = carousel.closest(".carousel-wrapper");
            const items = carousel.querySelectorAll(".item");

            if (items.length === 0) return;

            // Add scroll indicators
            addScrollIndicators(wrapper, carousel, items);

            // Add play overlays to video thumbnails
            addPlayOverlays(carousel);

            // Enhanced scroll behavior
            let isScrolling = false;
            carousel.addEventListener("scroll", function () {
                if (!isScrolling) {
                    updateScrollIndicators(wrapper, carousel, items);
                }
            });

            // Touch/swipe support for mobile
            addTouchSupport(carousel);
        });
    }

    function addScrollIndicators(wrapper, carousel, items) {
        const indicatorContainer = document.createElement("div");
        indicatorContainer.className = "scroll-indicator";

        const visibleItems = Math.floor(
            carousel.offsetWidth / items[0].offsetWidth
        );
        const totalPages = Math.ceil(items.length / visibleItems);

        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement("div");
            dot.className = "scroll-dot";
            if (i === 0) dot.classList.add("active");

            dot.addEventListener("click", function () {
                const scrollPosition = i * items[0].offsetWidth * visibleItems;
                carousel.scrollTo({
                    left: scrollPosition,
                    behavior: "smooth",
                });
            });

            indicatorContainer.appendChild(dot);
        }

        wrapper.appendChild(indicatorContainer);
    }

    function updateScrollIndicators(wrapper, carousel, items) {
        const dots = wrapper.querySelectorAll(".scroll-dot");
        const scrollLeft = carousel.scrollLeft;
        const itemWidth = items[0].offsetWidth;
        const visibleItems = Math.floor(carousel.offsetWidth / itemWidth);
        const currentPage = Math.floor(scrollLeft / (itemWidth * visibleItems));

        dots.forEach((dot, index) => {
            dot.classList.toggle("active", index === currentPage);
        });
    }

    function addPlayOverlays(carousel) {
        const thumbnails = carousel.querySelectorAll(".video-thumbnail");

        thumbnails.forEach((thumbnail) => {
            const playOverlay = document.createElement("div");
            playOverlay.className = "play-overlay";
            thumbnail.appendChild(playOverlay);
        });
    }

    function addTouchSupport(carousel) {
        let startX = 0;
        let scrollLeft = 0;
        let isDown = false;

        carousel.addEventListener("mousedown", (e) => {
            isDown = true;
            carousel.classList.add("dragging");
            startX = e.pageX - carousel.offsetLeft;
            scrollLeft = carousel.scrollLeft;
        });

        carousel.addEventListener("mouseleave", () => {
            isDown = false;
            carousel.classList.remove("dragging");
        });

        carousel.addEventListener("mouseup", () => {
            isDown = false;
            carousel.classList.remove("dragging");
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
            const x = e.touches[0].pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.scrollLeft = scrollLeft - walk;
        });
    }

    // ========== ENHANCED SCROLL CAROUSEL FUNCTION ==========
    window.scrollCarousel = function (carouselId, direction) {
        const carousel = document.getElementById(`carousel-${carouselId}`);
        if (!carousel) return;

        const items = carousel.querySelectorAll(".item");
        if (items.length === 0) return;

        const itemWidth = items[0].offsetWidth;
        const gap = 24; // From CSS gap
        const scrollAmount = itemWidth + gap;
        const currentScroll = carousel.scrollLeft;
        const maxScroll = carousel.scrollWidth - carousel.clientWidth;

        let newScroll;
        if (direction === 1) {
            newScroll = Math.min(currentScroll + scrollAmount, maxScroll);
        } else {
            newScroll = Math.max(currentScroll - scrollAmount, 0);
        }

        carousel.scrollTo({
            left: newScroll,
            behavior: "smooth",
        });

        // Update indicators after scroll
        setTimeout(() => {
            const wrapper = carousel.closest(".carousel-wrapper");
            updateScrollIndicators(wrapper, carousel, items);
        }, 300);
    };

    // ========== VIDEO LOADING ENHANCEMENT ==========
    function initVideoLoadingEnhancement() {
        const videoWrapper = document.querySelector(".video-wrapper");
        const iframe = document.querySelector("#youtube-iframe");

        if (!videoWrapper || !iframe) return;

        // Add autoplay and mute parameters to YouTube iframe
        const currentSrc = iframe.src;
        if (currentSrc && !currentSrc.includes("autoplay=1")) {
            const separator = currentSrc.includes("?") ? "&" : "?";
            iframe.src = currentSrc + separator + "autoplay=1&mute=1";
        }

        // Add loading skeleton
        const skeleton = document.createElement("div");
        skeleton.className = "video-skeleton";
        videoWrapper.appendChild(skeleton);

        // Remove skeleton when video loads
        iframe.addEventListener("load", function () {
            setTimeout(() => {
                skeleton.style.opacity = "0";
                setTimeout(() => {
                    if (skeleton.parentNode) {
                        skeleton.parentNode.removeChild(skeleton);
                    }
                }, 300);
            }, 500);
        });
    }

    // ========== INTERSECTION OBSERVER FOR ANIMATIONS ==========
    function initScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px",
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = "1";
                    entry.target.style.transform = "translateY(0)";
                } else {
                    // Reset animation when element goes out of view
                    entry.target.style.opacity = "0";
                    entry.target.style.transform = "translateY(30px)";
                }
            });
        }, observerOptions);

        // Observe elements for animation
        const animatedElements = document.querySelectorAll(
            ".detail-item, .item, .video-description"
        );
        animatedElements.forEach((el) => {
            el.style.opacity = "0";
            el.style.transform = "translateY(30px)";
            el.style.transition = "opacity 0.6s ease, transform 0.6s ease";
            observer.observe(el);
        });
    }

    // ========== SMOOTH SCROLLING FOR INTERNAL LINKS ==========
    function initSmoothScrolling() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const targetId = this.getAttribute("href").substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                }
            });
        });
    }

    // ========== KEYBOARD NAVIGATION ==========
    function initKeyboardNavigation() {
        document.addEventListener("keydown", function (e) {
            const activeCarousel = document.querySelector(".carousel:hover");

            if (activeCarousel) {
                if (e.key === "ArrowLeft") {
                    e.preventDefault();
                    const carouselId = activeCarousel.id.replace(
                        "carousel-",
                        ""
                    );
                    scrollCarousel(carouselId, -1);
                } else if (e.key === "ArrowRight") {
                    e.preventDefault();
                    const carouselId = activeCarousel.id.replace(
                        "carousel-",
                        ""
                    );
                    scrollCarousel(carouselId, 1);
                }
            }
        });
    }

    // ========== PERFORMANCE OPTIMIZATIONS ==========
    function initPerformanceOptimizations() {
        // Lazy loading for carousel images
        const images = document.querySelectorAll(".carousel img");

        if ("IntersectionObserver" in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src || img.src;
                        img.classList.remove("lazy");
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach((img) => {
                imageObserver.observe(img);
            });
        }

        // Debounced resize handler
        let resizeTimeout;
        window.addEventListener("resize", function () {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function () {
                // Recalculate carousel indicators
                const carousels = document.querySelectorAll(".carousel");
                carousels.forEach((carousel) => {
                    const wrapper = carousel.closest(".carousel-wrapper");
                    const items = carousel.querySelectorAll(".item");
                    if (items.length > 0) {
                        updateScrollIndicators(wrapper, carousel, items);
                    }
                });
            }, 250);
        });
    }

    // ========== ACCESSIBILITY ENHANCEMENTS ==========
    function initAccessibilityEnhancements() {
        // Add ARIA labels
        const carouselButtons = document.querySelectorAll(".arrow__btn");
        carouselButtons.forEach((button) => {
            const isLeft = button.classList.contains("left-arrow");
            button.setAttribute(
                "aria-label",
                isLeft ? "Previous videos" : "Next videos"
            );
            button.setAttribute("role", "button");
        });

        // Add focus management for carousel
        const carousels = document.querySelectorAll(".carousel");
        carousels.forEach((carousel) => {
            carousel.setAttribute("role", "region");
            carousel.setAttribute("aria-label", "Video carousel");

            const items = carousel.querySelectorAll(".item a");
            items.forEach((item, index) => {
                item.setAttribute("tabindex", index === 0 ? "0" : "-1");
            });
        });

        // Keyboard navigation within carousel
        document.addEventListener("keydown", function (e) {
            const focusedElement = document.activeElement;
            const isCarouselItem = focusedElement.closest(".item a");

            if (isCarouselItem) {
                const carousel = focusedElement.closest(".carousel");
                const items = Array.from(carousel.querySelectorAll(".item a"));
                const currentIndex = items.indexOf(focusedElement);

                let nextIndex;
                if (e.key === "ArrowRight") {
                    e.preventDefault();
                    nextIndex = Math.min(currentIndex + 1, items.length - 1);
                } else if (e.key === "ArrowLeft") {
                    e.preventDefault();
                    nextIndex = Math.max(currentIndex - 1, 0);
                }

                if (nextIndex !== undefined) {
                    items.forEach((item, index) => {
                        item.setAttribute(
                            "tabindex",
                            index === nextIndex ? "0" : "-1"
                        );
                    });
                    items[nextIndex].focus();
                }
            }
        });
    }

    // ========== INITIALIZE ALL FEATURES ==========
    initEnhancedCarousel();
    initVideoLoadingEnhancement();
    initScrollAnimations();
    initSmoothScrolling();
    initKeyboardNavigation();
    initPerformanceOptimizations();
    initAccessibilityEnhancements();

    // Add custom styles for dragging state
    const style = document.createElement("style");
    style.textContent = `
        .carousel.dragging {
            cursor: grabbing;
            scroll-behavior: auto;
        }
        
        .carousel.dragging .item {
            pointer-events: none;
        }
        
        .lazy {
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        @media (prefers-reduced-motion: reduce) {
            .carousel {
                scroll-behavior: auto;
            }
        }
    `;
    document.head.appendChild(style);

    console.log("🎬 Enhanced Video Player initialized successfully!");
});
