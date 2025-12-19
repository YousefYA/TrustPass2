<template>
    <div class="stage">
        <div class="card-wrap">
            <div class="card">
                <div class="brand">
                    <div class="logo">
                        <svg
                            width="30"
                            height="30"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"
                                fill="white"
                            />
                        </svg>
                    </div>
                    <h1>TRUSTPASS</h1>
                    <h2 v-if="step === 'creds'">Welcome Back</h2>
                    <h2 v-if="step === 'otp'">Two-Factor Auth</h2>
                    <div class="subtitle">Access your encrypted vault</div>
                </div>

                <form v-if="step === 'creds'" @submit.prevent="handlePassword">
                    <div class="field">
                        <input
                            v-model="email"
                            type="email"
                            placeholder="Email address"
                            required
                        />
                    </div>

                    <div class="field input-with-icon">
                        <input
                            :type="showPwd ? 'text' : 'password'"
                            v-model="password"
                            placeholder="Master Password"
                            required
                        />
                        <button
                            type="button"
                            class="eye-btn"
                            @click="showPwd = !showPwd"
                        >
                            {{ showPwd ? "🙈" : "👁️" }}
                        </button>
                    </div>

                    <button class="cta" type="submit" :disabled="loading">
                        <span v-if="!loading">Continue</span>
                        <span v-else>Verifying...</span>
                    </button>

                    <div style="text-align: center; margin-top: 15px">
                        <router-link
                            to="/register"
                            style="color: #6b7280; font-size: 0.9rem"
                            >Create an account</router-link
                        >
                    </div>
                </form>

                <form v-if="step === 'otp'" @submit.prevent="handleOtp">
                    <p
                        style="
                            color: #9ca3af;
                            font-size: 0.9rem;
                            text-align: center;
                            margin-bottom: 20px;
                        "
                    >
                        We sent a code to {{ email }}
                    </p>
                    <div class="field">
                        <input
                            v-model="otp"
                            placeholder="Enter verification code"
                            required
                            autofocus
                        />
                    </div>

                    <button class="cta" type="submit" :disabled="loading">
                        <span v-if="!loading">Unlock Vault</span>
                        <span v-else>Decrypting...</span>
                    </button>
                </form>

                <transition name="fade">
                    <p v-if="error" class="status-msg error">{{ error }}</p>
                </transition>

                <div class="glow-l"></div>
                <div class="glow-r"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { deriveKey } from "../crypto/kdf";
import { createVerifier } from "../crypto/verifier";

axios.defaults.withCredentials = true;
const router = useRouter();

const step = ref("creds"); // creds -> otp
const email = ref("");
const password = ref("");
const otp = ref("");
const error = ref(null);
const loading = ref(false);
const showPwd = ref(false);

// 1. Send Password Proof
async function handlePassword() {
    error.value = null;
    loading.value = true;

    try {
        // Get Salts
        const initRes = await axios.post("/api/login/init", {
            email: email.value,
        });
        const { salt1 } = initRes.data;

        // Derive Verifier
        const salt1Bytes = Uint8Array.from(atob(salt1), (c) => c.charCodeAt(0));
        const verifier = createVerifier(password.value, salt1Bytes);

        // Verify Password & Trigger OTP Email
        // Note: We use the NEW route /api/login/password
        await axios.post("/api/login/password", {
            email: email.value,
            verifier: verifier,
        });

        step.value = "otp"; // Move to next screen
    } catch (e) {
        if (e.response?.status === 404) error.value = "Account not found.";
        else if (e.response?.status === 401)
            error.value = "Incorrect password.";
        else error.value = "Connection error. Please try again.";
    } finally {
        loading.value = false;
    }
}

// 2. Verify OTP & Login
async function handleOtp() {
    error.value = null;
    loading.value = true;

    try {
        // Verify Code
        await axios.post("/api/otp/verify", {
            email: email.value,
            code: otp.value,
        });

        // Finalize Login (Check Session)
        const loginRes = await axios.post("/api/login/finalize", {
            email: email.value,
        });

        // Store Vault (Encrypted)
        localStorage.setItem("encrypted_vault", loginRes.data.encrypted_vault);

        // Success!
        router.push("/vault");
    } catch (e) {
        error.value = "Invalid code or session expired.";
    } finally {
        loading.value = false;
    }
}
</script>

<style scoped>
/* Keeping your existing styles */
.field {
    position: relative;
    margin-bottom: 15px;
}
.eye-btn {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
}
.status-msg {
    text-align: center;
    font-size: 0.9rem;
    margin-top: 15px;
}
.status-msg.error {
    color: #f87171;
}
</style>
