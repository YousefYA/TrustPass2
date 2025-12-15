<template>
    <div class="stage">
        <div class="card-wrap">
            <div class="card">
                <!-- Brand -->
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
                    <h2>Create Account</h2>
                    <div class="subtitle">Zero-knowledge password manager</div>
                </div>

                <!-- Form -->
                <form @submit.prevent="submit">
                    <div class="field">
                        <input
                            v-model="firstName"
                            type="text"
                            placeholder="First name"
                        />
                    </div>

                    <div class="field">
                        <input
                            v-model="lastName"
                            type="text"
                            placeholder="Last name"
                        />
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

                    <div class="field">
                        <input
                            v-model="recoveryAnswer"
                            placeholder="Recovery answer (never stored in plaintext)"
                            required
                        />
                    </div>

                    <button class="cta" type="submit">
                        <span class="overlay"></span>
                        <span>Create Account</span>
                    </button>

                    <p
                        v-if="error"
                        class="text-red-400 text-sm mt-3 text-center"
                    >
                        {{ error }}
                    </p>

                    <p
                        v-if="success"
                        class="text-green-400 text-sm mt-3 text-center"
                    >
                        Registration successful
                    </p>
                </form>

                <div class="glow-l"></div>
                <div class="glow-r"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

// 🔐 crypto modules (same as old working version)
import { deriveKey } from "../crypto/kdf";
import { createVerifier } from "../crypto/verifier";
import { encrypt } from "../crypto/encrypt";

// form state
const firstName = ref("");
const lastName = ref("");
const email = ref("");
const password = ref("");
const recoveryAnswer = ref("");
const showPwd = ref(false);

// ui state
const error = ref(null);
const success = ref(false);

async function submit() {
    error.value = null;
    success.value = false;

    try {
        // 1️⃣ generate salts
        const salt1 = crypto.getRandomValues(new Uint8Array(16));
        const salt2 = crypto.getRandomValues(new Uint8Array(16));

        // 2️⃣ derive keys (ZERO-KNOWLEDGE)
        const masterKey = deriveKey(password.value, salt1);
        const recoveryKey = deriveKey(recoveryAnswer.value, salt2);

        // 3️⃣ encrypt vault + master key
        const emptyVault = new TextEncoder().encode("{}");
        const encryptedVault = await encrypt(masterKey, emptyVault);
        const encryptedMasterKey = await encrypt(recoveryKey, masterKey);

        // 4️⃣ password verifier
        const verifier = createVerifier(password.value, salt1);

        // 5️⃣ send payload backend expects
        await axios.post("/api/register", {
            email: email.value,
            first_name: firstName.value || null,
            last_name: lastName.value || null,
            salt1: btoa(String.fromCharCode(...salt1)),
            salt2: btoa(String.fromCharCode(...salt2)),
            password_verifier: verifier,
            encrypted_vault: JSON.stringify(encryptedVault),
            encrypted_master_key: JSON.stringify(encryptedMasterKey),
        });

        success.value = true;
    } catch (e) {
        error.value =
            e.response?.data?.errors?.email?.[0] ||
            e.response?.data?.message ||
            "Registration failed";
    }
}
</script>
