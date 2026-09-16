/* =========================================================
   FIKES - MAIN JAVASCRIPT
   Versi aman untuk semua halaman frontend
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    /* =========================================================
       NAVBAR SCROLL EFFECT
    ========================================================= */
    const navbar = document.getElementById("navbar");

    if (navbar) {
        window.addEventListener("scroll", function () {
            if (window.scrollY > 20) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    }


    /* =========================================================
       MOBILE MENU
    ========================================================= */
    const menuToggle = document.getElementById("menuToggle");
    const navMenu = document.getElementById("navMenu");

    if (menuToggle && navMenu) {
        menuToggle.addEventListener("click", function () {
            navMenu.classList.toggle("active");

            menuToggle.innerHTML = navMenu.classList.contains("active")
                ? "✕"
                : "☰";
        });
    }


    /* =========================================================
       MOBILE DROPDOWN
    ========================================================= */
    document
        .querySelectorAll(
            ".has-dropdown > .nav-link, .has-dropdown > .dropdown-link"
        )
        .forEach(function (link) {

            link.addEventListener("click", function (e) {

                if (window.innerWidth <= 900) {
                    e.preventDefault();

                    const parent = this.parentElement;

                    if (parent) {
                        parent.classList.toggle("open");
                    }
                }

            });

        });


    /* =========================================================
       CLOSE MOBILE MENU AFTER CLICK
    ========================================================= */
    document.querySelectorAll(".nav-menu a").forEach(function (link) {

        link.addEventListener("click", function () {

            if (
                window.innerWidth <= 900 &&
                this.parentElement &&
                !this.parentElement.classList.contains("has-dropdown")
            ) {
                if (navMenu) {
                    navMenu.classList.remove("active");
                }

                if (menuToggle) {
                    menuToggle.innerHTML = "☰";
                }
            }

        });

    });


    /* =========================================================
       COUNTER ANIMATION
    ========================================================= */
    const counters = document.querySelectorAll(".stat-number");
    const statsSection = document.querySelector(".stats");

    let counterStarted = false;

    function startCounters() {

        if (counterStarted) {
            return;
        }

        counterStarted = true;

        counters.forEach(function (counter) {

            const target = parseInt(
                counter.getAttribute("data-target"),
                10
            );

            if (isNaN(target)) {
                return;
            }

            let current = 0;
            const increment = Math.max(1, Math.ceil(target / 60));

            function updateCounter() {

                current += increment;

                if (current >= target) {
                    counter.innerText = target + "+";
                } else {
                    counter.innerText = current;
                    requestAnimationFrame(updateCounter);
                }

            }

            updateCounter();

        });

    }


    /*
       PENTING:
       IntersectionObserver hanya boleh menerima Element.
       Halaman seperti Himpunan/UKM tidak mempunyai .stats,
       sehingga observer tidak boleh dipanggil dengan null.
    */
    if (statsSection && counters.length > 0) {

        const observer = new IntersectionObserver(
            function (entries) {

                entries.forEach(function (entry) {

                    if (entry.isIntersecting) {
                        startCounters();
                    }

                });

            },
            {
                threshold: 0.4
            }
        );

        observer.observe(statsSection);
    }


    /* =========================================================
       BACK TO TOP
    ========================================================= */
    const backTop = document.getElementById("backTop");

    if (backTop) {

        window.addEventListener("scroll", function () {

            if (window.scrollY > 500) {
                backTop.classList.add("show");
            } else {
                backTop.classList.remove("show");
            }

        });

        backTop.addEventListener("click", function () {

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });

        });

    }


    /* =========================================================
       CURRENT YEAR
    ========================================================= */
    const yearElement = document.getElementById("year");

    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }


    /* =========================================================
       CLOSE DROPDOWN WHEN CLICKING OUTSIDE
    ========================================================= */
    document.addEventListener("click", function (event) {

        if (!event.target.closest(".navbar")) {

            document
                .querySelectorAll(".nav-item.open")
                .forEach(function (item) {
                    item.classList.remove("open");
                });

        }

    });


    /* =========================================================
       SLIDER
    ========================================================= */
    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".slider-dot");

    if (slides.length > 0) {

        let currentIndex = 0;
        const slideDuration = 8000;

        function showSlide(index) {

            if (!slides[index]) {
                return;
            }

            slides.forEach(function (slide) {
                slide.classList.remove("active");
            });

            dots.forEach(function (dot) {
                dot.classList.remove("active");
            });

            slides[index].classList.add("active");

            if (dots[index]) {
                dots[index].classList.add("active");
            }

        }


        function nextSlide() {

            currentIndex++;

            if (currentIndex >= slides.length) {
                currentIndex = 0;
            }

            showSlide(currentIndex);

        }


        setInterval(nextSlide, slideDuration);

        showSlide(currentIndex);


        /* =====================================================
           TOMBOL NEXT / PREVIOUS
        ===================================================== */
        window.changeSlide = function (direction) {

            currentIndex += direction;

            if (currentIndex >= slides.length) {
                currentIndex = 0;
            }

            if (currentIndex < 0) {
                currentIndex = slides.length - 1;
            }

            showSlide(currentIndex);

        };


        /* =====================================================
           DOT NAVIGATION
        ===================================================== */
        window.currentSlide = function (index) {

            const newIndex = parseInt(index, 10) - 1;

            if (
                !isNaN(newIndex) &&
                newIndex >= 0 &&
                newIndex < slides.length
            ) {
                currentIndex = newIndex;
                showSlide(currentIndex);
            }

        };

    }


    /* =========================================================
       IMAGE LIGHTBOX - STRUKTUR ORGANISASI
    ========================================================= */
    const lightbox = document.getElementById("lightbox");
    const lightboxImage = document.getElementById("lightboxImage");
    const organizationImage = document.getElementById("organizationImage");
    const zoomButton = document.getElementById("zoomButton");
    const imageContainer = document.getElementById(
        "organizationImageContainer"
    );
    const lightboxClose = document.getElementById("lightboxClose");


    /*
       Lightbox hanya dijalankan jika elemennya memang ada.
       Halaman lain seperti Himpunan/UKM tidak mempunyai
       elemen lightbox tersebut.
    */
    if (
        lightbox &&
        lightboxImage &&
        organizationImage
    ) {

        function openLightbox() {

            lightboxImage.src = organizationImage.src;
            lightboxImage.alt = organizationImage.alt;

            lightbox.classList.add("active");
            lightbox.setAttribute("aria-hidden", "false");

            document.body.style.overflow = "hidden";

        }


        function closeLightbox() {

            lightbox.classList.remove("active");
            lightbox.setAttribute("aria-hidden", "true");

            document.body.style.overflow = "";

        }


        if (zoomButton) {
            zoomButton.addEventListener("click", openLightbox);
        }

        if (imageContainer) {
            imageContainer.addEventListener("click", openLightbox);
        }

        if (lightboxClose) {
            lightboxClose.addEventListener("click", closeLightbox);
        }

        lightbox.addEventListener("click", function (event) {

            if (event.target === lightbox) {
                closeLightbox();
            }

        });


        document.addEventListener("keydown", function (event) {

            if (
                event.key === "Escape" &&
                lightbox.classList.contains("active")
            ) {
                closeLightbox();
            }

        });

    }

});

/* =========================================================
   END MAIN.JS
========================================================= */
