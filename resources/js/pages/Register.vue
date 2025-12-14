<template>
    <div>
        <h2>Register</h2>

        <form @submit.prevent="register">
            <input v-model="email" type="email" placeholder="Email" required />
            <input
                v-model="password"
                type="password"
                placeholder="Password"
                required
            />
            <input
                v-model="recovery"
                type="text"
                placeholder="Recovery Answer"
                required
            />
            <button type="submit">Register</button>
        </form>

        <p v-if="error" style="color: red">{{ error }}</p>
        <p v-if="success" style="color: green">Registration successful</p>
    </div>
</template>

<script>
import axios from "axios";
import { deriveKey } from "../crypto/kdf";
import { createVerifier } from "../crypto/verifier";
import { encrypt } from "../crypto/encrypt";

export default {
    name: "Register",

    data() {
        return {
            email: "",
            password: "",
            recovery: "",
            error: null,
            success: false,
        };
    },

    methods: {
        async register() {
            this.error = null;
            this.success = false;

            try {
                const salt1 = crypto.getRandomValues(new Uint8Array(16));
                const salt2 = crypto.getRandomValues(new Uint8Array(16));

                const masterKey = deriveKey(this.password, salt1);
                const recoveryKey = deriveKey(this.recovery, salt2);

                const emptyVault = new TextEncoder().encode("{}");

                const encryptedVault = await encrypt(masterKey, emptyVault);
                const encryptedMasterKey = await encrypt(
                    recoveryKey,
                    masterKey
                );

                const verifier = createVerifier(this.password, salt1);

                const payload = {
                    email: this.email,
                    salt1: btoa(String.fromCharCode(...salt1)),
                    salt2: btoa(String.fromCharCode(...salt2)),
                    password_verifier: verifier,
                    encrypted_vault: JSON.stringify(encryptedVault),
                    encrypted_master_key: JSON.stringify(encryptedMasterKey),
                };

                await axios.post("/api/register", payload);
                this.success = true;
            } catch (e) {
                if (e.response?.data?.errors?.email) {
                    this.error = e.response.data.errors.email[0];
                } else {
                    this.error = "Registration failed";
                }
            }
        },
    },
};
</script>
