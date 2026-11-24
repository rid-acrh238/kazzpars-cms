
/* =========================
   NOCTURNE — INTERACTIONS
========================= */

// Update footer year automatically
const year = new Date().getFullYear();

const footerCopyright = document.querySelector(
    '.site-footer span'
);

if (footerCopyright) {
    footerCopyright.textContent =
        `© ${year} KAZZPARS · ALL RIGHTS RESERVED`;
}

// Navigation active state
const navLinks = document.querySelectorAll('.navigation a');

navLinks.forEach(link => {
    link.addEventListener('click', () => {
        navLinks.forEach(item => {
            item.classList.remove('active');
        });

        link.classList.add('active');
    });
});
