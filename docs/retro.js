
// =====================================
// X PORTAL — BOOT SCREEN, SCROLLSPY, BACK TO TOP, KONAMI CODE
// =====================================

(function () {
    "use strict";

    const prefersReducedMotion = window.matchMedia(
        "(prefers-reduced-motion: reduce)"
    ).matches;

    // -------------------------------------
    // Boot screen (layar memuat ala 90-an)
    // -------------------------------------
    const bootScreen = document.getElementById("boot-screen");
    let bootHidden = false;

    function hideBootScreen() {
        if (!bootScreen || bootHidden) return;
        bootHidden = true;

        if (prefersReducedMotion) {
            bootScreen.remove();
            return;
        }

        bootScreen.classList.add("is-hidden");
        window.setTimeout(() => bootScreen.remove(), 600);
    }

    if (bootScreen) {
        window.addEventListener("load", () => {
            window.setTimeout(hideBootScreen, 900);
        });

        // Fallback: jangan tahan halaman terlalu lama.
        window.setTimeout(hideBootScreen, 3500);
    }

    // -------------------------------------
    // Scrollspy: nav aktif mengikuti section
    // -------------------------------------
    const navLinks = Array.from(
        document.querySelectorAll('.navigation a[href^="#"]')
    );

    const sections = navLinks
        .map((link) => document.querySelector(link.getAttribute("href")))
        .filter(Boolean);

    if (sections.length && "IntersectionObserver" in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;

                    const activeHref = "#" + entry.target.id;

                    navLinks.forEach((link) => {
                        link.classList.toggle(
                            "active",
                            link.getAttribute("href") === activeHref
                        );
                    });
                });
            },
            {
                rootMargin: "-40% 0px -55% 0px",
                threshold: 0
            }
        );

        sections.forEach((section) => observer.observe(section));
    }

    // -------------------------------------
    // Back to top
    // -------------------------------------
    const backToTop = document.createElement("button");
    backToTop.type = "button";
    backToTop.id = "back-to-top";
    backToTop.setAttribute("aria-label", "Kembali ke atas");
    backToTop.innerHTML =
        '<i class="fa-solid fa-arrow-up" aria-hidden="true"></i> TOP';
    document.body.appendChild(backToTop);

    window.addEventListener(
        "scroll",
        () => {
            backToTop.classList.toggle("is-visible", window.scrollY > 400);
        },
        { passive: true }
    );

    backToTop.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: prefersReducedMotion ? "auto" : "smooth"
        });
    });

    // -------------------------------------
    // Easter egg: Konami code
    // -------------------------------------
    const KONAMI_CODE = [
        "ArrowUp",
        "ArrowUp",
        "ArrowDown",
        "ArrowDown",
        "ArrowLeft",
        "ArrowRight",
        "ArrowLeft",
        "ArrowRight",
        "b",
        "a"
    ];

    let konamiIndex = 0;

    function showSecretWindow() {
        if (document.getElementById("konami-dialog")) return;

        const dialog = document.createElement("div");
        dialog.id = "konami-dialog";
        dialog.setAttribute("role", "dialog");
        dialog.setAttribute("aria-modal", "true");
        dialog.setAttribute("aria-label", "Secret unlocked");

        dialog.innerHTML =
            '<div class="retro-window">' +
            '  <div class="retro-window-titlebar">' +
            "    <span>SECRET.EXE</span>" +
            '    <button type="button" class="retro-window-close" aria-label="Tutup">X</button>' +
            "  </div>" +
            '  <div class="retro-window-body">' +
            "    <p>★ CONGRATULATIONS, INTERNET TRAVELER! ★</p>" +
            "    <p>Kamu menemukan kode rahasianya.</p>" +
            "    <p>Sebagai hadiah, kamu resmi diangkat jadi warga internet. 🎉</p>" +
            '    <button type="button" class="retro-window-ok">OK</button>' +
            "  </div>" +
            "</div>";

        document.body.appendChild(dialog);

        const closeDialog = () => dialog.remove();

        dialog
            .querySelector(".retro-window-close")
            .addEventListener("click", closeDialog);

        dialog
            .querySelector(".retro-window-ok")
            .addEventListener("click", closeDialog);

        dialog.addEventListener("click", (event) => {
            if (event.target === dialog) closeDialog();
        });

        document.addEventListener("keydown", function closeOnEscape(event) {
            if (event.key !== "Escape") return;
            closeDialog();
            document.removeEventListener("keydown", closeOnEscape);
        });

        dialog.querySelector(".retro-window-ok").focus();
    }

    document.addEventListener("keydown", (event) => {
        const key =
            event.key.length === 1 ? event.key.toLowerCase() : event.key;

        if (key === KONAMI_CODE[konamiIndex]) {
            konamiIndex += 1;

            if (konamiIndex === KONAMI_CODE.length) {
                konamiIndex = 0;
                showSecretWindow();
            }

            return;
        }

        konamiIndex = key === KONAMI_CODE[0] ? 1 : 0;
    });
})();
