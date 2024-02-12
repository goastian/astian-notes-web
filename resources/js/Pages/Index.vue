<template>
    <div class="card bg-light" v-for="(item, index) in notes" :key="index">
        <div class="card-head text-center fw-bold">
            <button class="btn float-start mx-2" @click="update(item.id)">
                <i class="bi bi-eye-fill h5 text-success"></i>
            </button>
            {{ item.titulo }}
            <v-modal
                :item="item"
                :target="'remove'.concat(item.id)"
                btn_primary="btn float-end mx-2"
                @is-accepted="remove"
            >
                <template v-slot:button>
                    <i class="bi bi-trash-fill h5 text-warning"></i>
                </template>
                <template v-slot:head> Eliminar </template>
                <template v-slot:body>
                    Esta nota no se podrá recuperar luego que sea eliminada
                </template>
            </v-modal>
        </div>
        <div class="card-body" v-html="item.cuerpo"></div>
        <div class="footer">
            <button
                v-show="item.etiqueta"
                class="btn"
                @click="search(item.etiqueta_id)"
            >
                <i class="bi bi-pin-angle-fill"></i>
                {{ item.etiqueta }}
            </button>
        </div>
    </div>
    <div class="foot">
        <v-pagination :pages="pages" @changed-page="newPage"></v-pagination>
    </div>
</template>
<script>
export default {
    data() {
        return {
            notes: null,
            pages: {},
            form: {
                per_page: 10,
                page: 1,
            },
        };
    },

    mounted() {
        this.listenEvents();
        if (this.$route.params.id) {
            this.getNotesFilter();
        } else {
            this.getNotes();
        }
    },

    watch: {
        $route(to, from) {
            if (to.params.id) {
                this.form.etiqueta_id = to.params.id;
                this.getNotesFilter();
            }

            if (!to.params.id) {
                this.getNotes();
            }
        },
    },

    methods: {
        search(id) {
            this.$router.push({
                name: "notes",
                params: {
                    id: id,
                },
            });
        },

        update(id) {
            this.$router.push({
                name: "notes.create",
                params: {
                    id: id,
                },
            });
        },

        getNotes() {
            this.$host
                .get("/api/notes", {
                    params: {
                        per_page: this.form.per_page,
                        page: this.form.page,
                    },
                })
                .then((res) => {
                    this.notes = res.data.data;
                    this.pages = res.data.meta.pagination;
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        getNotesFilter() {
            this.$host
                .get("/api/notes", { params: this.form })
                .then((res) => {
                    this.notes = res.data.data;
                    this.pages = res.data.meta.pagination;
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        remove(event) {
            this.$host
                .delete(event.links.destroy)
                .then((res) => {
                    this.getNotesFilter();
                })
                .catch((err) => {
                    if (err.response) {
                        this.errors = err.response.data.message;
                    }
                });
        },

        newPage(event) {
            this.form.page = event;
            this.getNotes();
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreNoteEvent", (e) => {
                    this.getNotes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateNoteEvent", (e) => {
                    this.getNotes();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyNoteEvent", (e) => {
                    this.getNotesFilter();
                });
        },
    },
};
</script>

<style scoped lang="scss">
.card {
    margin-left: 0.5%;
    margin-top: 0.5%;
    margin-bottom: 0.5%;
    margin-right: 0;

    @media (min-width: 240px) {
    }

    @media (min-width: 940px) {
        width: 32%;
        float: left;
    }
}

button:hover i {
    font-size: larger;
    font-family: var(--font);
}

.foot {
    clear: both;
    margin-top: 80vh;
}
</style>
