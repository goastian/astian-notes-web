import { createRouter, createWebHistory } from "vue-router";

import VNotes from "../Pages/Index.vue";
import VNotesCreate from "../Pages/Editor.vue";
import VTagCreate from "../Pages/Tag.vue";

const routes = [
    { path: "/:id?", name: "notes", component: VNotes },
    { path: "/notes/:id?", name: "notes.create", component: VNotesCreate },
    { path: "/tags", name: "tag.create", component: VTagCreate },
];

export const router = createRouter({
    history: createWebHistory(),
    routes,
});
