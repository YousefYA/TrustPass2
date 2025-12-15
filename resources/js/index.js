import { createRouter, createWebHistory } from "vue-router";

import Login from "../pages/Login.vue";
import Register from "../pages/Register.vue";
import Vault from "../pages/Vault.vue";

const routes = [
    { path: "/", redirect: "/login" }, // 👈 DEFAULT
    { path: "/login", component: Login },
    { path: "/register", component: Register },
    { path: "/vault", component: Vault },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
