import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/login",
        name: "login",
        component: () => import("../Login/Login.vue"),
        meta: { auth: false },
    },
    {
        path: "/:id?",
        name: "notes",
        component: () => import("../Pages/Index.vue"),
        meta: { auth: true },
    },
    {
        path: "/notes/:id?",
        name: "notes.create",
        component: () => import("../Pages/Editor.vue"),
        meta: { auth: true },
    },
    {
        path: "/tags",
        name: "tag.create",
        component: () => import("../Pages/Tag.vue"),
        meta: { auth: true },
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
