<template>
    <div>
        <div class="add text-color" v-show="!pages.total">
            <div class="add-header h3">Do you have tasks to DO?</div>
            <div class="add-body">
                <button class="btn" @click="showForm">
                    <i class="bi bi-journal-plus text-primary"></i>
                </button>
            </div>
            <div class="add-footer">
                Don't forget them, keep them saved and remember whenever you
                want.
            </div>
        </div>

        <div class="card" v-for="(item, index) in notes" :key="index">
            <div class="card-head text-center text-color fw-bold">
                <button class="btn float-start mx-2" @click="update(item.id)">
                    <i class="bi bi-eye-fill h5 text-success"></i>
                </button>
                {{ item.title }}
                <v-modal
                    :item="item"
                    :target="'remove'.concat(item.id)"
                    btn_primary="btn float-end mx-2"
                    @is-accepted="remove"
                >
                    <template v-slot:button>
                        <i class="bi bi-trash-fill h5 text-warning"></i>
                    </template>
                    <template v-slot:head> Remove Tag </template>
                    <template v-slot:body>
                        Are you sure you want to perform this action?
                    </template>
                </v-modal>
            </div>
            <div class="card-body text-color" v-html="item.body"></div>
            <div class="">
                <button
                    v-show="item.tag"
                    class="btn text-color"
                    @click="search(item.tag_id)"
                >
                    <i class="bi bi-pin-angle-fill"></i>
                    {{ item.tag }}
                </button>
            </div>
        </div>
        <v-pagination
            v-show="pages.total > form.per_page"
            :pages="pages"
            @changed-page="newPage"
        ></v-pagination>
    </div>
</template>
<script>
export default {
    data() {
        return {
            notes: {},
            pages: {},
            form: {
                per_page: 10,
                page: 1,
                tag_id: null,
            },
        };
    },

    created() {
        this.form.tag_id = this.$route.params.id;

        this.listenEvents();
    },

    mounted() {
        this.getNotes();
    },

    watch: {
        /**
         * Search notes when the route change
         * @param {*} to
         * @param {*} from
         */
        $route(to, from) {
            if (to.params.id) {
                this.form.tag_id = to.params.id;
            } else {
                this.form.tag_id = null;
            }
            this.getNotes();
        },
    },

    methods: {
        /**
         * Search notes using id
         */
        search(id) {
            this.$router.push({
                name: "notes",
                params: {
                    id: id,
                },
            });
        },

        /**
         * Show the form to register new note
         */
        showForm() {
            this.$router.push({ name: "notes.create" });
        },

        /**
         * Show datails to the note selected
         * @param {*} id
         */
        update(id) {
            this.$router.push({
                name: "notes.create",
                params: {
                    id: id,
                },
            });
        },

        /**
         * Get the notes
         */
        getNotes() {
            /**
             * Search usgin the tag_id
             */
            if (this.form.tag_id) {
                this.$host
                    .get("/api/notes", {
                        params: this.form,
                    })
                    .then((res) => {
                        this.notes = res.data.data;
                        this.pages = res.data.meta.pagination;
                    })
                    .catch((err) => {});
            } else {
                /**
                 * Search basic form
                 */
                this.$host
                    .get("/api/notes", {
                        params: {
                            per_page: 10,
                            page: 1,
                        },
                    })
                    .then((res) => {
                        this.notes = res.data.data;
                        this.pages = res.data.meta.pagination;
                    })
                    .catch((err) => {});
            }
        },

        /**
         * 
         */
        remove(event) {
            this.$host
                .delete(event.links.destroy)
                .then((res) => { 
                    this.form.tag_id = to.params.id;
                    this.getNotes();
                })
                .catch((err) => {});
        },

        newPage(event) {
            this.form.page = event;
            this.getNotes();
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("StoreNoteEvent", (e) => {
                    this.getNotes();
                });

            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("UpdateNoteEvent", (e) => {
                    this.getNotes();
                });

            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("DestroyNoteEvent", (e) => {
                    this.getNotes();
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

.add {
    text-align: center;
    margin: 5% auto;
}

.add-body {
    margin: 1% 0;
}

.add-body i {
    font-size: 5em;
}
</style>
