import { createRouter, createWebHistory } from "vue-router";
import Register from "../pages/Register.vue";
import Login from "../pages/Login.vue";
import Recover from "../pages/Recover.vue";
import Vault from "../pages/Vault.vue";

const routes = [
    { path: "/register", component: Register },
    { path: "/login", component: Login },
    { path: "/recover", component: Recover },
    { path: "/vault", component: Vault },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ✅ THIS LINE IS WHAT YOU WERE MISSING
export default router;
