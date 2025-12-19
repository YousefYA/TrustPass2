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
                    <div class="field">
                        <input v-model="firstName" placeholder="First name" />
                    </div>
                    <div class="field">
                        <input v-model="lastName" placeholder="Last name" />
                    </div>

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
                            tabindex="-1"
                        >
                            <svg
                                v-if="!showPwd"
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"
                                ></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                            <svg
                                v-else
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M1 12s4-8 11-8 11 8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                ></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
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

                    <div
                        class="password-checklist"
                        :class="{ active: password.length > 0 }"
                    >
                        <div class="check-item" :class="{ met: isLengthMet }">
                            <span class="icon"></span> At least 12 characters
                        </div>
                        <div class="check-item" :class="{ met: hasNumber }">
                            <span class="icon"></span> Includes a number
                        </div>
                        <div class="check-item" :class="{ met: hasSymbol }">
                            <span class="icon"></span> Includes a symbol
                        </div>
                        <div class="check-item" :class="{ met: isMatch }">
                            <span class="icon"></span> Passwords match
                        </div>
                    </div>

                    <div class="field">
                        <input
                            v-model="recoveryAnswer"
                            placeholder="Recovery Phrase (Save this safely!)"
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

                <transition name="fade">
                    <p v-if="error" class="status-msg error">{{ error }}</p>
                </transition>

                <transition name="fade">
                    <p v-if="success" class="status-msg success">
                        Registration successful! Redirecting...
                    </p>
                </transition>

                <div class="glow-l"></div>
                <div class="glow-r"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue"; // ✅ Import computed
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
const passwordConfirm = ref("");
const recoveryAnswer = ref("");
const showPwd = ref(false);
const otp = ref("");

let payload = null;

// ✅ COMPUTED PROPERTIES (Prevents Regex Errors in Template)
const isLengthMet = computed(() => password.value.length >= 12);
const hasNumber = computed(() => /[0-9]/.test(password.value));
const hasSymbol = computed(() => /[!@#$%^&*(),.?":{}|<>]/.test(password.value));
const isMatch = computed(
    () =>
        password.value === passwordConfirm.value &&
        passwordConfirm.value.length > 0
);

async function startOtp() {
    error.value = null;

    // 🔒 Strict Validation using the computed props
    if (!isLengthMet.value) {
        error.value =
            "Password must be at least 12 characters (NIST Guideline).";
        return;
    }
    if (!hasNumber.value) {
        error.value = "Password must include at least one number.";
        return;
    }
    if (!hasSymbol.value) {
        error.value = "Password must include at least one special character.";
        return;
    }
    if (!isMatch.value) {
        error.value = "Passwords do not match.";
        return;
    }

    try {
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

        await axios.post("/api/otp/send", { email: email.value });
        step.value = "otp";
    } catch {
        error.value = "Failed to send verification code. Check network.";
    }
}

async function verifyOtp() {
    error.value = null;
    try {
        await axios.post("/api/otp/verify", {
            email: email.value,
            code: otp.value,
        });
        await axios.post("/api/register", payload);

        success.value = true;
        step.value = "done";
        setTimeout(() => {
            router.push("/vault");
        }, 2000);
    } catch {
        error.value = "Invalid or expired verification code";
    }
}
</script>

<style scoped>
/* 1. Layout Fixes */
.field {
    position: relative;
    margin-bottom: 15px;
}

/* 2. Professional Icons */
.eye-btn {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
    transition: color 0.2s;
    padding: 0;
    display: flex;
    align-items: center;
}
.eye-btn:hover {
    color: #4f46e5;
}

/* 3. Animated Password Checklist */
.password-checklist {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease-out, opacity 0.4s;
    opacity: 0;
    margin-bottom: 0;
}
.password-checklist.active {
    max-height: 200px; /* Expands smoothly */
    opacity: 1;
    margin-bottom: 20px;
}

.check-item {
    font-size: 0.85rem;
    color: #6b7280;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateX(0);
}

/* The little circle icon */
.check-item .icon {
    width: 6px;
    height: 6px;
    background-color: #374151;
    border-radius: 50%;
    margin-right: 10px;
    transition: all 0.3s ease;
}

/* When requirement is MET */
.check-item.met {
    color: #10b981; /* Green */
    transform: translateX(5px); /* Slide effect */
}
.check-item.met .icon {
    background-color: #10b981;
    box-shadow: 0 0 8px rgba(16, 185, 129, 0.4); /* Glow */
    transform: scale(1.3);
}

/* Status Messages */
.status-msg {
    text-align: center;
    font-size: 0.9rem;
    margin-top: 15px;
}
.status-msg.error {
    color: #f87171;
}
.status-msg.success {
    color: #4ade80;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
