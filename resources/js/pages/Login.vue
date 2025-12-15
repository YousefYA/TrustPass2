<template>
    <section class="card-wrapper">
        <div class="glass-card">
            <!-- Header -->
            <div class="top">
                <div class="icon">
                    <div class="shield"></div>
                </div>

                <h1 class="brand">TRUSTPASS</h1>
                <h2 class="subtitle">Sign in</h2>
                <p class="muted">Access your encrypted vault</p>
            </div>

            <!-- Login form -->
            <form @submit.prevent="login">
                <div class="field">
                    <input
                        v-model="email"
                        type="email"
                        placeholder="Email address"
                        required
                    />
                </div>

                <div class="field">
                    <input
                        v-model="password"
                        :type="showPwd ? 'text' : 'password'"
                        placeholder="Password"
                        required
                    />
                    <button
                        type="button"
                        class="eye-btn"
                        @click="showPwd = !showPwd"
                    >
                        👁️
                    </button>
                </div>

                <button class="cta" type="submit">Sign In</button>

                <p v-if="error" class="error">
                    {{ error }}
                </p>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>or</span>
            </div>

            <!-- Register link -->
            <router-link to="/register" class="link-btn">
                Create a new account
            </router-link>
        </div>
    </section>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

import { deriveKey } from "../crypto/kdf";
import { createVerifier } from "../crypto/verifier";

const email = ref("");
const password = ref("");
const showPwd = ref(false);
const error = ref(null);

async function login() {
    error.value = null;

    try {
        // STEP 1: get salt
        const initRes = await axios.post("/api/login/init", {
            email: email.value,
        });

        const salt1 = Uint8Array.from(atob(initRes.data.salt1), (c) =>
            c.charCodeAt(0)
        );

        // STEP 2: derive key locally (never sent)
        deriveKey(password.value, salt1);

        // STEP 3: create proof
        const verifier = createVerifier(password.value, salt1);

        // STEP 4: verify
        await axios.post("/api/login/verify", {
            email: email.value,
            password_verifier: verifier,
        });

        // SUCCESS → vault
        window.location.href = "/vault";
    } catch {
        error.value = "Invalid email or password";
    }
}
</script>

<style scoped>
/* layout */
.card-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at top, #0b1020, #05070f);
}

.glass-card {
    width: 420px;
    padding: 36px;
    border-radius: 22px;
    background: linear-gradient(
        180deg,
        rgba(255, 255, 255, 0.04),
        rgba(255, 255, 255, 0.01)
    );
    border: 1px solid rgba(255, 255, 255, 0.08);
    backdrop-filter: blur(14px);
    box-shadow: 0 30px 90px rgba(0, 0, 0, 0.65);
    color: #eaf2ff;
}

/* header */
.top {
    text-align: center;
    margin-bottom: 24px;
}

.icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 10px;
}

.shield {
    width: 64px;
    height: 64px;
    border-radius: 14px;
    background: linear-gradient(135deg, #6b46ff, #1fb6ff);
    box-shadow: 0 10px 25px rgba(107, 70, 255, 0.45);
}

.brand {
    margin: 8px 0 0;
    font-size: 30px;
    font-weight: 800;
    letter-spacing: 2px;
}

.subtitle {
    margin-top: 10px;
    font-size: 18px;
    font-weight: 700;
}

.muted {
    margin-top: 8px;
    font-size: 14px;
    color: rgba(200, 210, 240, 0.65);
}

/* fields */
.field {
    position: relative;
    margin-bottom: 14px;
}

input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    background: rgba(10, 15, 30, 0.6);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: #fff;
    font-size: 14px;
}

input::placeholder {
    color: rgba(200, 210, 240, 0.4);
}

.eye-btn {
    position: absolute;
    right: 10px;
    top: 9px;
    background: none;
    border: none;
    color: #9aa7ff;
    cursor: pointer;
}

/* buttons */
.cta {
    width: 100%;
    padding: 12px;
    border-radius: 14px;
    border: none;
    cursor: pointer;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(90deg, #6b46ff, #1fb6ff);
    margin-top: 6px;
}

.link-btn {
    display: block;
    margin-top: 16px;
    text-align: center;
    color: #9aa7ff;
    text-decoration: none;
}

.divider {
    margin: 18px 0;
    text-align: center;
    color: rgba(200, 210, 240, 0.4);
}

.error {
    margin-top: 12px;
    text-align: center;
    color: #ff6b6b;
}
</style>
