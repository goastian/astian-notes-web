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

        <div class="boxes">
            <div class="box" v-for="(item, index) in notes" :key="index">
                <div class="header">
                    <button class="btn float-start" @click="update(item.id)">
                        <i class="bi bi-eye-fill h5 text-success"></i>
                    </button>
                    {{ item.title }}

                    <v-modal
                        :item="item"
                        :target="'remove'.concat(item.id)"
                        btn_primary="btn float-end"
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
                <div class="body fw-light" v-html="item.body"></div>
                <div class="">
                    <button
                        v-show="item.tag"
                        class="btn text-light"
                        @click="search(item.tag_id)"
                    >
                        <i class="bi bi-pin-angle-fill text-light"></i>
                        {{ item.tag }}
                    </button>
                </div>
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
                per_page: 15,
                page: 1,
                tag_id: null,
            },
        };
    },

    created() {
        this.form.tag_id = this.$route.params.id;

    },
    
    mounted() {
        this.getNotes();
        this.listenEvents();
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
         * Remove items
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

        /**
         * Change pagination
         * @param {*} event
         */
        newPage(event) {
            this.form.page = event;
            this.getNotes();
        },

        /**
         * Listen Events
         */
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

.boxes {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 2%;
}

.box {
    flex: 1 1 auto;
    margin-left: 1%;
    border: 1px double;
    padding: 1%;
    margin-top: 1%;
    border-color: var(--primary);
    background-color: var(--code);
    color: var(--white);
    border-radius: 0.5%;
    @media (min-width: 800px) {
        max-width: 48%;
    }
    @media (min-width: 940px) {
        max-width: 32%;
    }
}

.box .header {
    text-align: center;
    margin-bottom: 5%;
}

.box .body {
    text-align: justify;
}
</style>
