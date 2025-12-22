import { createRouter, createWebHistory } from "vue-router";
import Register from "../pages/Register.vue";
import Login from "../pages/Login.vue";
// import Recover from "../pages/Recover.vue"; // Uncomment if you have this
import Vault from "../pages/Vault.vue";
import axios from "axios";

const routes = [
    {
        path: "/register",
        component: Register,
        meta: { guest: true }, // 🟢 Only for guests (not logged in)
    },
    {
        path: "/login",
        component: Login,
        meta: { guest: true }, // 🟢 Only for guests
    },
    {
        path: "/vault",
        component: Vault,
        meta: { requiresAuth: true }, // 🔒 PROTECTED: Needs Auth
    },
    // Redirect unknown paths to login
    {
        path: "/:pathMatch(.*)*",
        redirect: "/login",
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// 🛡️ THE SECURITY BOUNCER
router.beforeEach(async (to, from, next) => {
    // 1. Check if the route requires authentication (Like /vault)
    if (to.meta.requiresAuth) {
        // CHECK A: Do we have the Encrypted Vault in LocalStorage?
        // In a Zero-Knowledge app, if this is missing, the user CANNOT use the app.
        const hasVaultData = localStorage.getItem("encrypted_vault");

        if (!hasVaultData) {
            return next("/login"); // 🚫 Kick them out immediately
        }

        // CHECK B: Is the Server Session valid?
        // We ping the server to verify the cookie is actually logged in.
        try {
            await axios.get("/me"); // Ensure you have this route in web.php
            next(); // ✅ All good, proceed to Vault
        } catch (e) {
            // Session expired or invalid cookie
            localStorage.removeItem("encrypted_vault"); // Clear stale data
            return next("/login"); // 🚫 Kick them out
        }
    }

    // 2. Prevent Logged-in users from seeing Login/Register pages
    else if (to.meta.guest) {
        const hasVaultData = localStorage.getItem("encrypted_vault");
        // If they have data, assume they are logged in and send to vault
        if (hasVaultData) {
            return next("/vault");
        }
        next();
    }

    // 3. Default: Allow access
    else {
        next();
    }
});

export default router;
