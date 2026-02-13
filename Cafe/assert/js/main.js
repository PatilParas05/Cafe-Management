/**
 * MAIN JAVASCRIPT
 * Landing page effects + Clean Admin Panel
 */

(function () {
    "use strict";

    /* ============================================
       CHECK IF ADMIN PAGE
    ============================================ */

    const isAdmin =
        document.body.classList.contains("admin-layout") ||
        document.body.classList.contains("login-container");

    /* ============================================
       PARTICLE SYSTEM (Landing Only)
    ============================================ */

    const ParticleSystem = {
        container: null,
        particleCount: 25,

        init() {
            if (isAdmin) return;

            this.container = document.getElementById("particles");
            if (!this.container) return;

            for (let i = 0; i < this.particleCount; i++) {
                const particle = document.createElement("div");
                particle.className = "particle";

                particle.style.left = Math.random() * 100 + "%";
                particle.style.width = Math.random() * 8 + 3 + "px";
                particle.style.height = particle.style.width;
                particle.style.animationDelay = Math.random() * 15 + "s";
                particle.style.animationDuration =
                    Math.random() * 10 + 10 + "s";

                this.container.appendChild(particle);
            }
        },
    };

    /* ============================================
       RIPPLE EFFECT
    ============================================ */

    const RippleEffect = {
        init() {
            document.querySelectorAll(".ripple").forEach((element) => {
                element.addEventListener("click", (e) => {
                    const ripple = document.createElement("span");
                    const rect = element.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.className = "ripple-effect";
                    ripple.style.width = ripple.style.height = size + "px";
                    ripple.style.left = x + "px";
                    ripple.style.top = y + "px";

                    element.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            });
        },
    };

    /* ============================================
       CARD TILT (Landing Only)
    ============================================ */

    const CardTilt = {
        init() {
            if (isAdmin) return;

            document.querySelectorAll(".card").forEach((card) => {
                card.addEventListener("mousemove", (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;

                    const rotateX = (y - centerY) / 10;
                    const rotateY = (centerX - x) / 10;

                    card.style.transform = `
                        perspective(1000px)
                        rotateX(${rotateX}deg)
                        rotateY(${rotateY}deg)
                        translateY(-10px)
                    `;
                });

                card.addEventListener("mouseleave", () => {
                    card.style.transform = "";
                });
            });
        },
    };

    /* ============================================
       SCROLL PROGRESS (Landing Only)
    ============================================ */

    const ScrollProgress = {
        bar: null,

        init() {
            if (isAdmin) return;

            this.bar = document.createElement("div");
            this.bar.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                height: 3px;
                background: linear-gradient(90deg, #654321, #8B4513);
                z-index: 9999;
                pointer-events: none;
                transition: width 0.1s ease;
            `;

            document.body.appendChild(this.bar);

            window.addEventListener("scroll", () => {
                const scrollTop = window.pageYOffset;
                const docHeight =
                    document.documentElement.scrollHeight -
                    window.innerHeight;
                const scrollPercent = (scrollTop / docHeight) * 100;
                this.bar.style.width = scrollPercent + "%";
            });
        },
    };

    /* ============================================
       FORM VALIDATION (All Pages)
    ============================================ */

    const FormValidation = {
        init() {
            document.querySelectorAll("form").forEach((form) => {
                form.addEventListener("submit", (e) => {
                    const inputs = form.querySelectorAll(
                        "input[required], textarea[required]"
                    );

                    let valid = true;

                    inputs.forEach((input) => {
                        if (!input.value.trim()) {
                            input.style.borderColor = "red";
                            valid = false;
                        } else {
                            input.style.borderColor = "";
                        }
                    });

                    if (!valid) e.preventDefault();
                });
            });
        },
    };

    /* ============================================
       ADMIN CLEANUP FIX
    ============================================ */

    function adminFix() {
        if (!isAdmin) return;

        // Remove any blocking elements
        document
            .querySelectorAll(".scroll-progress, .cursor-dot")
            .forEach((el) => el.remove());

        document.body.style.overflow = "auto";
    }

    /* ============================================
       INITIALIZE
    ============================================ */

    function init() {
        ParticleSystem.init();
        RippleEffect.init();
        CardTilt.init();
        ScrollProgress.init();
        FormValidation.init();
        adminFix();

        console.log("System initialized correctly ✅");
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", init);
    } else {
        init();
    }
})();

/* ========================================
   ADMIN PANEL EXTRA JS
======================================== */

document.addEventListener("click", function (e) {
    if (
        e.target.classList.contains("delete-confirm") ||
        e.target.closest(".delete-confirm")
    ) {
        if (!confirm("Are you sure you want to delete this item?")) {
            e.preventDefault();
        }
    }
});
