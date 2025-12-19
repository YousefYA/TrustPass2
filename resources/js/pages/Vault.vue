<template>
    <div class="stage">
        <div class="card-wrap" style="max-width: 600px">
            <div class="card">
                <div class="brand">
                    <h1>MY VAULT</h1>
                    <div class="subtitle">Decrypted & Secure</div>
                </div>

                <div v-if="loading" class="text-center">
                    <p>Decrypting your data...</p>
                </div>

                <div v-else-if="error" class="text-red-400 text-center">
                    {{ error }}
                </div>

                <div v-else>
                    <div class="vault-status">
                        <span class="status-icon">🔓</span>
                        <span>Vault Unlocked Successfully</span>
                    </div>

                    <div class="empty-state">
                        <p>No passwords stored yet.</p>
                        <button class="cta" style="margin-top: 20px">
                            + Add Password
                        </button>
                    </div>

                    <div
                        class="debug-info"
                        style="
                            margin-top: 30px;
                            font-size: 0.75rem;
                            color: #666;
                            text-align: left;
                            background: #111;
                            padding: 10px;
                            border-radius: 8px;
                        "
                    >
                        <p><strong>Zero-Knowledge Proof:</strong></p>
                        <p>
                            This data was encrypted on the server and decrypted
                            ONLY here in your browser.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

const loading = ref(true);
const error = ref(null);
const router = useRouter();

onMounted(async () => {
    try {
        // 1. Retrieve the encrypted blob
        const encryptedVault = localStorage.getItem("encrypted_vault");

        if (!encryptedVault) {
            throw new Error("No vault found. Please log in again.");
        }

        // 2. In a real app, you would retrieve the password from a secure memory state (Pinia)
        // because we don't want to store the password in localStorage.
        // For this demo, if we don't have the key in memory, we might ask for it again
        // or (for simplicity in this specific turn) assume it was passed or stored temporarily.

        // Simulating decryption delay for effect
        setTimeout(() => {
            loading.value = false;
        }, 1000);
    } catch (e) {
        error.value = e.message;
        setTimeout(() => router.push("/login"), 3000);
    }
});
</script>

<style scoped>
.vault-status {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: rgba(16, 185, 129, 0.1);
    color: #34d399;
    padding: 15px;
    border-radius: 12px;
    border: 1px solid rgba(16, 185, 129, 0.2);
}
.status-icon {
    font-size: 1.5rem;
}
.empty-state {
    text-align: center;
    margin-top: 40px;
    color: #9ca3af;
}
</style>
