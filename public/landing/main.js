/* ================= GSAP SETUP ================= */
gsap.registerPlugin(ScrollTrigger);

/* ================= HERO SCROLL FADE ================= */
gsap.to("#hero .hero-inner", {
  opacity: 0,
  scale: 0.95,
  y: 50,
  ease: "none",
  scrollTrigger: {
    trigger: "#hero",
    start: "top top",
    end: "bottom+=300 top",
    scrub: true,
  },
});

/* ================= HERO FLOATING SHIELD ================= */
gsap.to(".shield-box", {
  y: -20,
  rotationY: 10,
  duration: 3,
  ease: "power1.inOut",
  yoyo: true,
  repeat: -1,
});

gsap.utils.toArray(".orbit").forEach((dot, i) => {
  gsap.to(dot, {
    motionPath: {
      path: [
        { x: 0, y: -50 },
        { x: 50, y: 0 },
        { x: 0, y: 50 },
        { x: -50, y: 0 },
        { x: 0, y: -50 },
      ],
      autoRotate: false,
    },
    duration: 4,
    repeat: -1,
    delay: i * 0.6,
    ease: "linear",
  });
});

/* ================= ZERO TRUST SVG PATHS ================= */
document.querySelectorAll(".zt-path").forEach((path, index) => {
  const length = path.getTotalLength();

  path.style.strokeDasharray = length;
  path.style.strokeDashoffset = length;

  gsap.to(path, {
    strokeDashoffset: 0,
    duration: 1.5,
    delay: index * 0.8,
    scrollTrigger: {
      trigger: "#zero-trust",
      start: "top 70%",
    },
  });
});

/* ================= ZERO TRUST NODES ================= */
gsap.utils.toArray(".zt-node").forEach((node, i) => {
  gsap.from(node, {
    opacity: 0,
    scale: 0.7,
    duration: 0.6,
    delay: i * 0.4,
    scrollTrigger: {
      trigger: "#zero-trust",
      start: "top 70%",
    },
  });
});

/* ================= ZERO TRUST STEPS ================= */
gsap.from(".zt-step", {
  opacity: 0,
  y: 30,
  stagger: 0.15,
  duration: 0.6,
  scrollTrigger: {
    trigger: ".zt-steps",
    start: "top 80%",
  },
});

/* ================= CORE SECURITY CARDS ================= */
gsap.from(".pillar-card", {
  opacity: 0,
  y: 40,
  stagger: 0.15,
  duration: 0.7,
  scrollTrigger: {
    trigger: "#security",
    start: "top 70%",
  },
});

/* ================= ARCHITECTURE TABS DATA ================= */
const architectureData = {
  registration: [
    "Create master password",
    "Derive local key",
    "Encrypt credentials",
    "Store encrypted vault",
  ],
  login: [
    "Password entry",
    "Local key derivation",
    "Encrypted request",
    "Server verification",
  ],
  vault: [
    "Decrypt vault locally",
    "Access encrypted data",
    "Continuous validation",
    "Zero plaintext exposure",
  ],
  recovery: [
    "Recovery verification",
    "Rebuild encryption keys",
    "Re-encrypt vault",
    "Restore secure access",
  ],
};

const stepsContainer = document.getElementById("archSteps");

/* ================= LOAD ARCHITECTURE STEPS ================= */
function loadArchitectureSteps(key) {
  stepsContainer.innerHTML = "";

  architectureData[key].forEach((text, i) => {
    const div = document.createElement("div");
    div.className = "arch-step";
    div.innerHTML = `<b>${i + 1}</b> ${text}`;
    stepsContainer.appendChild(div);
  });

  gsap.from(".arch-step", {
    opacity: 0,
    y: 20,
    stagger: 0.1,
    duration: 0.5,
  });
}

/* ================= TAB INTERACTION ================= */
document.querySelectorAll(".arch-tab").forEach((tab) => {
  tab.addEventListener("click", () => {
    document
      .querySelectorAll(".arch-tab")
      .forEach((t) => t.classList.remove("active"));
    tab.classList.add("active");

    loadArchitectureSteps(tab.dataset.tab);
  });
});

/* ================= INITIAL LOAD ================= */
loadArchitectureSteps("registration");

/* ================= THREAT CARDS ================= */
gsap.from(".threat-card", {
  opacity: 0,
  x: -40,
  stagger: 0.2,
  duration: 0.7,
  scrollTrigger: {
    trigger: "#threats",
    start: "top 75%",
  },
});

/* ================= CTA FLOAT ================= */
gsap.utils.toArray(".cta-icons svg").forEach((icon, i) => {
  gsap.to(icon, {
    y: -10,
    duration: 2.5,
    yoyo: true,
    repeat: -1,
    delay: i * 0.2,
    ease: "power1.inOut",
  });
});
