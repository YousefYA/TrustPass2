<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrustPass — Zero Trust Security</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <link rel="stylesheet" href="/landing/style.css">
</head>
<body class="loading-state">

    <div id="preloader">
        <div class="loader-content">
            <div class="logo-loader">
                <i data-lucide="shield"></i>
            </div>
            <div class="progress-track">
                <div class="progress-bar"></div> </div>
            <div class="loading-text">Initializing Secure Environment...</div>
        </div>
    </div>

    <nav class="navbar">
        <div class="nav-inner">
            <div class="brand">
                <i data-lucide="shield" class="brand-icon"></i>
                <span>TRUSTPASS</span>
            </div>
            <div class="nav-links">
                <a href="#architecture">Architecture</a>
                <a href="#security">Security Model</a>
                <a href="/login" class="btn btn-primary">Get Started</a>
            </div>
        </div>
    </nav>

    <section id="hero">
        <div class="hero-content">
            <div class="hero-badge">
                <i data-lucide="shield-check"></i> Zero-Trust Architecture
            </div>
            
            <h1 class="hero-title">
                <span class="block-reveal">Security You Can</span>
                <span class="block-reveal gradient-text">Feel, Not Just Read</span>
            </h1>

            <p class="hero-sub">
                <span class="highlight blue">Client-side encryption</span> • 
                <span class="highlight purple">Continuous verification</span> • 
                <span class="highlight cyan">No plaintext ever</span>
            </p>

            <div class="hero-actions">
                <a href="#architecture" class="btn btn-primary">Explore Architecture</a>
                <a href="/register" class="btn btn-ghost">View Security Model</a>
            </div>

            <div class="hero-visual">
                <div class="shield-wrapper">
                    <div class="shield-core">
                        <i data-lucide="shield"></i>
                    </div>
                </div>
                <div class="orbit-system">
                    <div class="orbit-dot dot-1"></div>
                    <div class="orbit-dot dot-2"></div>
                    <div class="orbit-dot dot-3"></div>
                    <div class="orbit-dot dot-4"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="zero-trust">
        <div class="section-head">
            <h2>Zero-Trust Architecture</h2>
            <p>Your password never leaves your device. The server verifies — but never knows.</p>
        </div>

        <div class="diagram-panel">
            <div class="diagram-stage">
                <div class="node node-user">
                    <div class="icon-box"><i data-lucide="user"></i></div>
                    <span>User</span>
                </div>

                <svg class="connection-lines" viewBox="0 0 600 100" preserveAspectRatio="none">
                    <path class="draw-line line-1" d="M 50 50 L 250 50" />
                    <path class="draw-line line-2" d="M 350 50 L 550 50" />
                </svg>

                <div class="node node-blob">
                    <div class="icon-box blob-pulse"><i data-lucide="lock"></i></div>
                    <div class="blob-ring"></div>
                    <span>Encrypted Blob</span>
                </div>

                <div class="node node-server">
                    <div class="icon-box"><i data-lucide="server"></i></div>
                    <span>Server</span>
                </div>
            </div>

            <div class="diagram-steps">
                <div class="step-card"><b>1</b> Enter Password</div>
                <div class="step-card"><b>2</b> Local Hashing</div>
                <div class="step-card"><b>3</b> Send Hash</div>
                <div class="step-card"><b>4</b> Verification</div>
            </div>
        </div>
    </section>

    <section id="security">
        <div class="section-head">
            <h2>Core Security Guarantees</h2>
        </div>
        <div class="pillars-grid">
            <div class="pillar-card">
                <div class="pillar-icon"><i data-lucide="lock"></i></div>
                <h3>Client-Side Encryption</h3>
                <p>All encryption happens before data leaves your device.</p>
                <div class="hover-line"></div>
            </div>
            <div class="pillar-card">
                <div class="pillar-icon"><i data-lucide="eye-off"></i></div>
                <h3>Zero-Knowledge</h3>
                <p>We never see your secrets — by design.</p>
                <div class="hover-line"></div>
            </div>
            <div class="pillar-card">
                <div class="pillar-icon"><i data-lucide="shield"></i></div>
                <h3>Continuous Verification</h3>
                <p>Every request is cryptographically re-validated.</p>
                <div class="hover-line"></div>
            </div>
            <div class="pillar-card">
                <div class="pillar-icon"><i data-lucide="key"></i></div>
                <h3>Military-Grade Crypto</h3>
                <p>AES-256-GCM and Argon2id hashing.</p>
                <div class="hover-line"></div>
            </div>
        </div>
    </section>

    <section id="architecture">
        <div class="section-head">
            <h2>System Architecture</h2>
        </div>

        <div class="tabs-wrapper">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="registration">Registration</button>
                <button class="tab-btn" data-tab="login">Login</button>
                <button class="tab-btn" data-tab="vault">Vault Access</button>
            </div>
            
            <div class="tabs-body">
                <div id="tab-content-area" class="tab-content">
                    </div>
            </div>
        </div>
    </section>

    <section id="threats">
        <div class="section-head">
            <h2>Our Threat Model</h2>
            <p>Security through transparency.</p>
        </div>

        <div class="threat-stack">
            <div class="threat-card">
                <div class="threat-icon red"><i data-lucide="server-crash"></i></div>
                <div class="threat-text">
                    <h3>What if servers are breached?</h3>
                    <p>Attackers only see encrypted blobs. Without your master password, data is useless.</p>
                    <div class="badge-safe"><i data-lucide="check-circle-2"></i> Your data remains secure</div>
                </div>
                <div class="barrier-orange"></div> </div>

            <div class="threat-card">
                <div class="threat-icon red"><i data-lucide="database"></i></div>
                <div class="threat-text">
                    <h3>What if the database is stolen?</h3>
                    <p>Encryption keys never leave your device. Stolen data looks like random noise.</p>
                    <div class="badge-safe"><i data-lucide="check-circle-2"></i> Your data remains secure</div>
                </div>
                <div class="barrier-orange"></div>
            </div>
        </div>
    </section>

    <section id="cta">
        <div class="cta-card">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            
            <div class="cta-content">
                <div class="float-icons">
                    <i data-lucide="shield"></i> <i data-lucide="lock"></i> <i data-lucide="key"></i>
                </div>
                <h2>Ready to Secure Your Digital Life?</h2>
                <p>Join thousands of security-conscious users.</p>
                <div class="hero-actions">
                    <a href="/register" class="btn btn-primary">Get Started</a>
                </div>
                <div class="trust-row">
                    <span><span class="dot green"></span> Open Source</span>
                    <span><span class="dot green"></span> Audited</span>
                    <span><span class="dot green"></span> No Tracking</span>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 TrustPass. Zero-Knowledge Architecture.</p>
    </footer>

    <script src="/landing/main.js"></script>
    <script>lucide.createIcons();</script>
</body>
</html>a