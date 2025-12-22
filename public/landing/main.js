gsap.registerPlugin(ScrollTrigger);

// 1. PRELOADER SEQUENCE (Orange Bar)
window.addEventListener("load", () => {
    const tl = gsap.timeline();

    // Animate Orange Bar
    tl.to(".progress-bar", {
        width: "100%",
        duration: 1.5,
        ease: "power2.inOut",
    })
        .to(".loading-text", { opacity: 0, duration: 0.5 })
        .to("#preloader", {
            y: "-100%",
            duration: 0.8,
            ease: "power4.inOut",
            onComplete: () => {
                document.body.classList.remove("loading-state");
                startSiteAnimations();
            },
        });
});

function startSiteAnimations() {
    // 2. HERO ANIMATIONS
    // Text Reveal
    gsap.from(".block-reveal", {
        y: 30,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: "power2.out",
    });
    // Buttons
    gsap.from(".hero-actions", {
        y: 20,
        opacity: 0,
        duration: 0.8,
        delay: 0.6,
    });

    // 3D Shield Float & Tilt (Continuous)
    gsap.to(".shield-core", {
        y: -20,
        rotationY: 15, // 3D Tilt
        duration: 4,
        yoyo: true,
        repeat: -1,
        ease: "power1.inOut",
    });

    // Orbiting Particles (Simple circular motion via rotation)
    gsap.to(".orbit-system", {
        rotation: 360,
        duration: 10,
        repeat: -1,
        ease: "linear",
    });

    // Scroll Fade Out
    gsap.to("#hero", {
        opacity: 0,
        scale: 0.95,
        y: 50,
        scrollTrigger: {
            trigger: "#hero",
            start: "top top",
            end: "bottom center",
            scrub: true,
        },
    });

    // 3. ZERO TRUST DIAGRAM (Path Drawing)
    const diagTl = gsap.timeline({
        scrollTrigger: { trigger: "#zero-trust", start: "top 70%" },
    });

    // Nodes pop in
    diagTl
        .from(".node", {
            scale: 0,
            opacity: 0,
            stagger: 0.2,
            duration: 0.5,
            ease: "back.out(1.7)",
        })
        // SVG Paths Draw (using stroke-dasharray trick in CSS + opacity here for simplicity)
        .to(".draw-line", { opacity: 1, duration: 0.5, stagger: 0.3 }, "-=0.5")
        // Steps slide up
        .from(
            ".step-card",
            { y: 20, opacity: 0, stagger: 0.1, duration: 0.4 },
            "-=0.2"
        );

    // 4. TRUST PILLARS
    gsap.from(".pillar-card", {
        y: 50,
        opacity: 0,
        duration: 0.6,
        stagger: 0.15,
        scrollTrigger: { trigger: "#security", start: "top 80%" },
    });

    // 5. ARCHITECTURE TABS LOGIC
    const archData = {
        registration: [
            "User enters Master Password",
            "Browser derives encryption key (scrypt)",
            "Vault encrypted locally (AES-256)",
            "Encrypted blob sent to server",
        ],
        login: [
            "User inputs email",
            "Server sends Salts (Zero-Knowledge)",
            "Browser calculates Verifier hash",
            "Server checks Verifier",
        ],
        vault: [
            "Encrypted blob downloaded",
            "Decrypted in browser memory",
            "Data displayed to user",
            "Keys wiped on logout",
        ],
    };

    const contentArea = document.getElementById("tab-content-area");
    const tabs = document.querySelectorAll(".tab-btn");

    function loadTab(key) {
        // Slide Out Left
        gsap.to(contentArea, {
            x: -20,
            opacity: 0,
            duration: 0.2,
            onComplete: () => {
                // Update Content
                contentArea.innerHTML = archData[key]
                    .map(
                        (step, i) => `
                <div class="arch-step-item">
                    <div class="arch-num">${i + 1}</div>
                    <div>${step}</div>
                </div>
            `
                    )
                    .join("");

                // Slide In Right
                gsap.fromTo(
                    contentArea,
                    { x: 20, opacity: 0 },
                    { x: 0, opacity: 1, duration: 0.3 }
                );
                gsap.from(".arch-step-item", {
                    x: 10,
                    opacity: 0,
                    stagger: 0.05,
                    delay: 0.1,
                });
            },
        });
    }

    tabs.forEach((btn) => {
        btn.addEventListener("click", () => {
            tabs.forEach((t) => t.classList.remove("active"));
            btn.classList.add("active");
            loadTab(btn.dataset.tab);
        });
    });

    // Initial Load
    loadTab("registration");

    // 6. THREAT MODEL (Barrier Animation)
    gsap.utils.toArray(".threat-card").forEach((card) => {
        const barrier = card.querySelector(".barrier-orange");
        const tl = gsap.timeline({
            scrollTrigger: { trigger: card, start: "top 85%" },
        });

        tl.from(card, { x: -30, opacity: 0, duration: 0.5 }).to(barrier, {
            scaleX: 1,
            duration: 0.8,
            ease: "power2.out",
        }); // Barrier Expands
    });

    // 7. FINAL CTA
    gsap.from(".cta-card", {
        y: 50,
        opacity: 0,
        duration: 0.8,
        scrollTrigger: { trigger: "#cta", start: "top 80%" },
    });

    // Floating Icons in CTA
    gsap.to(".float-icons i", {
        y: -10,
        duration: 2,
        stagger: 0.3,
        yoyo: true,
        repeat: -1,
        ease: "sine.inOut",
    });
}
