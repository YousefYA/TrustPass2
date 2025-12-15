a<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>TrustPass — Zero Trust Security</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Core Styles -->
    <link rel="stylesheet" href="/landing/style.css" />

    <!-- GSAP -->
    <script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
    <script src="https://unpkg.com/gsap@3/dist/ScrollTrigger.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
  </head>

  <body>
    <!-- ================= NAV ================= -->
    <header class="nav">
      <div class="nav-inner">
        <div class="logo">
          <i data-lucide="shield"></i>
          <span>TRUSTPASS</span>
        </div>

        <nav class="nav-links">
          <a href="#architecture">Architecture</a>
          <a href="#security-model">Security Model</a>
        </nav>

        <a href="/login" class="btn primary">Get Started</a>
      </div>
    </header>

    <!-- ================= HERO ================= -->
    <section id="hero">
      <div class="hero-inner">
        <div class="hero-badge">
          <i data-lucide="shield"></i>
          Zero-Trust Architecture
        </div>

        <h1 class="hero-title">
          <span>Security You Can</span>
          <span class="gradient">Feel, Not Just Read</span>
        </h1>

        <p class="hero-sub">
          <span class="blue">Client-side encryption</span> •
          <span class="purple">Continuous verification</span> •
          <span class="cyan">No plaintext ever</span>
        </p>

        <div class="hero-actions">
          <a class="btn primary">Explore the Architecture</a>
          <a href="/register" class="btn ghost">Join TrustPass</a>
        </div>

        <!-- Floating Shield -->
        <div class="hero-float">
          <div class="shield-box">
            <i data-lucide="shield"></i>

            <span class="orbit"></span>
            <span class="orbit"></span>
            <span class="orbit"></span>
            <span class="orbit"></span>
          </div>
        </div>

        <div class="scroll-indicator">Scroll to explore</div>
      </div>
    </section>

    <!-- ================= ZERO TRUST ================= -->
    <section id="zero-trust">
      <h2>Zero-Trust Architecture</h2>
      <p class="section-sub">
        Your password never leaves your device. The server verifies — but never
        knows.
      </p>

      <div class="zt-panel">
        <!-- Diagram -->
        <div class="zt-diagram">
          <div class="zt-node user">
            <i data-lucide="user"></i>
            <span>User</span>
            <small>Your Device</small>
          </div>

          <svg viewBox="0 0 800 200">
            <defs>
              <linearGradient
                id="zt-gradient"
                x1="0%"
                y1="0%"
                x2="100%"
                y2="0%"
              >
                <stop offset="0%" stop-color="#3b82f6" />
                <stop offset="100%" stop-color="#8b5cf6" />
              </linearGradient>
            </defs>

            <path class="zt-path" d="M150 100 L350 100" />
            <path class="zt-path" d="M450 100 L650 100" />
          </svg>

          <div class="zt-node encrypted">
            <i data-lucide="lock"></i>
            <span>Encrypted</span>
            <small>SHA-1 Hash</small>
          </div>

          <div class="zt-node server">
            <i data-lucide="server"></i>
            <span>Server</span>
            <small>Verifies Only</small>
          </div>
        </div>

        <!-- Steps -->
        <div class="zt-steps">
          <div class="zt-step"><b>1</b> Password Entry</div>
          <div class="zt-step"><b>2</b> Local Hashing</div>
          <div class="zt-step"><b>3</b> Encrypted Request</div>
          <div class="zt-step"><b>4</b> Server Verification</div>
        </div>
      </div>
    </section>

    <!-- ================= CORE GUARANTEES ================= -->
    <section id="security">
      <h2>Core Security Guarantees</h2>
      <p class="section-sub">
        Built from first principles. No trust assumptions.
      </p>

      <div class="pillars">
        <div class="pillar-card">
          <i data-lucide="lock"></i>
          <h3>Client-Side Encryption</h3>
          <p>All encryption happens before data leaves your device.</p>
        </div>

        <div class="pillar-card">
          <i data-lucide="eye-off"></i>
          <h3>Zero-Knowledge</h3>
          <p>We never see your secrets — by design.</p>
        </div>

        <div class="pillar-card">
          <i data-lucide="refresh-ccw"></i>
          <h3>Continuous Verification</h3>
          <p>Every request is cryptographically re-validated.</p>
        </div>

        <div class="pillar-card">
          <i data-lucide="shield"></i>
          <h3>Military-Grade Crypto</h3>
          <p>Industry-standard, peer-reviewed algorithms.</p>
        </div>
      </div>
    </section>

    <!-- ================= ARCHITECTURE ================= -->
    <section id="architecture">
      <h2>System Architecture</h2>
      <p class="section-sub">End-to-end flow without trust assumptions.</p>

      <div class="arch-tabs">
        <button class="arch-tab active" data-tab="registration">
          Registration
        </button>
        <button class="arch-tab" data-tab="login">Login</button>
        <button class="arch-tab" data-tab="vault">Vault Access</button>
        <button class="arch-tab" data-tab="recovery">Recovery</button>
      </div>

      <div class="arch-panel">
        <div id="archSteps" class="arch-steps"></div>
      </div>
    </section>

    <!-- ================= THREAT MODEL ================= -->
    <section id="threats">
      <h2>Threat Model Awareness</h2>
      <p class="section-sub">Security through transparency.</p>

      <div class="threat-card">
        <h3>What if your servers are breached?</h3>
        <p>Attackers only see encrypted vault blobs.</p>
        <span class="safe">Your data remains secure</span>
      </div>

      <div class="threat-card">
        <h3>What if your database is stolen?</h3>
        <p>Encryption keys never leave your device.</p>
        <span class="safe">Your data remains secure</span>
      </div>

      <div class="threat-card">
        <h3>What if traffic is intercepted?</h3>
        <p>Only encrypted hashes ever travel the network.</p>
        <span class="safe">Your data remains secure</span>
      </div>
    </section>

    <!-- ================= FINAL CTA ================= -->
    <section id="cta">
      <h2>Security Without Trust</h2>
      <p>Designed to fail safely — every time.</p>

      <div class="cta-icons">
        <i data-lucide="lock"></i>
        <i data-lucide="shield"></i>
        <i data-lucide="database"></i>
        <i data-lucide="key"></i>
      </div>

      <div class="cta-actions">
        <a class="btn primary">Get Started</a>
        <a class="btn ghost">Read the Docs</a>
      </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer>
      <div class="footer-grid">
        <div>
          <h4>Product</h4>
          <a>Features</a>
          <a>Architecture</a>
          <a>Security</a>
        </div>

        <div>
          <h4>Resources</h4>
          <a>Documentation</a>
          <a>Whitepaper</a>
          <a>API</a>
        </div>

        <div>
          <h4>Company</h4>
          <a>About</a>
          <a>Privacy</a>
          <a>Contact</a>
        </div>

        <div>
          <h4>Trust</h4>
          <span>Zero-Knowledge Verified</span>
          <span>No Plaintext Storage</span>
        </div>
      </div>
    </footer>

    <!-- ================= SCRIPTS ================= -->
    <script src="/landing/main.js"></script>
    <script>
      lucide.createIcons();
    </script>a
  </body>
</html>
