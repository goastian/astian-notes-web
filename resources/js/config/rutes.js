import { createRouter, createWebHistory } from "vue-router";

import VNotes from "../Pages/Index.vue";
import VNotesCreate from "../Pages/Editor.vue";
import VTagCreate from "../Pages/Tag.vue";

const routes = [
    { path: "/:id?", name: "notes", component: VNotes, meta: { auth: true } },
    {
        path: "/notes/:id?",
        name: "notes.create",
        component: VNotesCreate,
        meta: { auth: true }
    },
    {
        path: "/tags",
        name: "tag.create",
        component: VTagCreate,
        meta: { auth: true }
    },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
