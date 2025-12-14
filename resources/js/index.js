import { createRouter, createWebHistory } from "vue-router";
import Register from "../pages/Register.vue";

const routes = [{ path: "/register", component: Register }];

export default createRouter({
    history: createWebHistory(),
    routes,
});
