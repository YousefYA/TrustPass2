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
                                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"
                                fill="white"
                            />
                        </svg>
                    </div>

                    <h1>TRUSTPASS</h1>
                    <h2 v-if="step === 'form'">Create Account</h2>
                    <h2 v-if="step === 'otp'">Verify Email</h2>

                    <div class="subtitle">Zero-knowledge password manager</div>
                </div>

                <form v-if="step === 'form'" @submit.prevent="startOtp">
                    <div class="field-row">
                        <div class="field">
                            <input
                                v-model="firstName"
                                placeholder="First name"
                            />
                        </div>
                        <div class="field">
                            <input v-model="lastName" placeholder="Last name" />
                        </div>
                    </div>

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
                            :type="showPwd ? 'text' : 'password'"
                            v-model="password"
                            placeholder="Master Password (min 12 chars)"
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

                    <div class="field">
                        <input
                            type="password"
                            v-model="passwordConfirm"
                            placeholder="Confirm Master Password"
                            required
                        />
                    </div>

                    <div class="password-requirements" v-if="password">
                        <p :class="{ valid: password.length >= 12 }">
                            • At least 12 characters
                        </p>
                        <p
                            :class="{
                                valid:
                                    password === passwordConfirm &&
                                    passwordConfirm.length > 0,
                            }"
                        >
                            • Passwords match
                        </p>
                    </div>

                    <div class="field">
                        <input
                            v-model="recoveryAnswer"
                            placeholder="Recovery Phrase (for account recovery)"
                            required
                        />
                    </div>

                    <button class="cta" type="submit">
                        <span>Create Account</span>
                    </button>
                </form>

                <form v-if="step === 'otp'" @submit.prevent="verifyOtp">
                    <div class="field">
                        <input
                            v-model="otp"
                            placeholder="Enter verification code"
                            required
                        />
                    </div>

                    <button class="cta" type="submit">
                        <span>Verify Code</span>
                    </button>
                </form>

                <p v-if="error" class="text-red-400 text-sm mt-3 text-center">
                    {{ error }}
                </p>

                <p
                    v-if="success"
                    class="text-green-400 text-sm mt-3 text-center"
                >
                    Registration successful! Redirecting...
                </p>

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
import { encrypt } from "../crypto/encrypt";

axios.defaults.withCredentials = true;
const router = useRouter();

// UI state
const step = ref("form");
const error = ref(null);
const success = ref(false);

// Form data
const firstName = ref("");
const lastName = ref("");
const email = ref("");
const password = ref("");
const passwordConfirm = ref(""); // ✅ Added confirmation
const recoveryAnswer = ref("");
const showPwd = ref(false);
const otp = ref("");

// Crypto payload cache
let payload = null;

/**
 * STEP 1: Validate & Send OTP
 */
async function startOtp() {
    error.value = null;

    // 🔒 NIST & Validation Checks
    if (password.value.length < 12) {
        error.value =
            "Password is too short. NIST guidelines recommend at least 12 characters.";
        return;
    }

    if (password.value.length > 128) {
        error.value = "Password is too long (max 128 characters).";
        return;
    }

    if (password.value !== passwordConfirm.value) {
        error.value = "Passwords do not match.";
        return;
    }

    try {
        // Prepare crypto ONCE
        const salt1 = crypto.getRandomValues(new Uint8Array(16));
        const salt2 = crypto.getRandomValues(new Uint8Array(16));

        const masterKey = deriveKey(password.value, salt1);
        const recoveryKey = deriveKey(recoveryAnswer.value, salt2);

        const emptyVault = new TextEncoder().encode("{}");
        const encryptedVault = await encrypt(masterKey, emptyVault);
        const encryptedMasterKey = await encrypt(recoveryKey, masterKey);

        const verifier = createVerifier(password.value, salt1);

        payload = {
            email: email.value,
            first_name: firstName.value || null,
            last_name: lastName.value || null,
            salt1: btoa(String.fromCharCode(...salt1)),
            salt2: btoa(String.fromCharCode(...salt2)),
            password_verifier: verifier,
            encrypted_vault: JSON.stringify(encryptedVault),
            encrypted_master_key: JSON.stringify(encryptedMasterKey),
        };

        // Send OTP only
        await axios.post("/api/otp/send", { email: email.value });

        step.value = "otp";
    } catch {
        error.value = "Failed to send verification code. Check your network.";
    }
}

/**
 * STEP 2: Verify OTP → Register
 */
async function verifyOtp() {
    error.value = null;

    try {
        await axios.post("/api/otp/verify", {
            email: email.value,
            code: otp.value,
        });

        // Now register (OTP session exists)
        await axios.post("/api/register", payload);

        success.value = true;
        step.value = "done";

        // Redirect to vault after delay
        setTimeout(() => {
            router.push("/vault");
        }, 2000);
    } catch {
        error.value = "Invalid or expired verification code";
    }
}
</script>

<style scoped>
/* Optional: Add this to your CSS for the hints */
.password-requirements {
    font-size: 0.8rem;
    margin-bottom: 15px;
    text-align: left;
    padding-left: 5px;
    color: #666;
}
.password-requirements p {
    margin: 2px 0;
    transition: color 0.3s ease;
}
.password-requirements p.valid {
    color: #4ade80; /* Green */
}
.field-row {
    display: flex;
    gap: 10px;
}
.field-row .field {
    flex: 1;
}
</style>
