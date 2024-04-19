<template>
    <div class="row content-editor">
        <div class="category">
            <input
                type="text"
                v-model="form.title"
                class="form-control form-control-sm"
                placeholder="Title ..."
            />
            <span
                class="errors"
                v-for="(item, index) in errors.title"
                :key="index"
                v-text="item"
            ></span>
        </div>
        <div class="category">
            <select class="form-control form-control-sm" v-model="form.tag_id">
                <option
                    v-for="(item, index) in tags"
                    :key="index"
                    :value="item.id"
                >
                    {{ item.tag }}
                </option>
            </select>
        </div>
        <div class="editor">
            <div id="toolbar">
                <select class="ql-header">
                    <option value="1">Heading 1</option>
                    <option value="2">Heading 2</option>
                    <option value="3">Heading 3</option>
                    <option value="4">Heading 4</option>
                    <option value="5">Heading 5</option>
                    <option value="6">Heading 6</option>
                    <option selected>Normal</option>
                </select>

                <button class="ql-bold">Bold</button>
                <button class="ql-italic">Italic</button>
                <button class="ql-underline">Underline</button>
                <button class="ql-blockquote">blockquote</button>
                <button class="ql-code-block">code-block</button>

                <button class="ql-align" value="center">
                    <i class="bi bi-text-center"></i>
                </button>
                <button class="ql-align" value="justify">
                    <i class="bi bi-justify"></i>
                </button>
                <button class="ql-align" value="right">
                    <i class="bi bi-justify-right"></i>
                </button>

                <button class="ql-list" value="ordered">
                    <i class="fas fa-list-ol"></i>
                </button>

                <button class="ql-list" value="bullet">
                    <i class="fas fa-list-ul"></i>
                </button>

                <button class="ql-link"><i class="fas fa-link"></i></button>
            </div>
            <div id="editor"></div>
            <span
                class="errors"
                v-for="(item, index) in errors.body"
                :key="index"
                v-text="item"
            ></span>

            <button v-show="!update" class="btn btn-primary mt-3" @click="save">
                Save note <i class="bi bi-floppy-fill mx-2"></i>
            </button>
            <button
                v-show="update"
                class="btn btn-ternary mt-3"
                @click="updateNote"
            >
                Update Note <i class="bi bi-cloud-upload mx-2"></i>
            </button>

            <p class="text-primary mt-2" v-show="errors.message">
                {{ errors.message }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    emits: ["message"],

    data() {
        return {
            update: false,
            quill: null,
            errors: {
                message: null,
            },
            form: {
                body: null,
            },
            tags: {},
            button: null,
        };
    },

    created() {
        this.getTags();
        if (this.$route.params.id) {
            this.update = true;
            this.getNote(this.$route.params.id);
        }
    },
    
    mounted() {
        this.quill = new Quill("#editor", {
            placeholder: "Write a new note",
            readOnly: false,
            theme: "snow",
            modules: {
                toolbar: "#toolbar",
            },
        });

        this.listenEvents();
    },

    watch: {
        $route(to, from) {
            if (to.params.id) {
                this.update = true;
                this.getNote(to.params.id);
            }
            if (!to.params.id) {
                this.quill.root.innerHTML = "";
                this.update = false;
                this.form = {};
            }
        },
    },

    methods: {
        getTags() {
            this.$host
                .get("/api/tags", {
                    params: {
                        per_page: 500,
                    },
                })
                .then((res) => {
                    this.tags = res.data.data;
                })
                .catch((err) => {});
        },

        getNote(id) {
            this.$host
                .get("/api/notes/" + id)
                .then((res) => {
                    this.form = res.data.data;
                    this.quill.root.innerHTML = this.form.body;
                })
                .catch((err) => {});
        },

        updateNote($event) {
            this.button = $event.target;
            this.button.disabled = true;

            this.errors.message = "";
            this.form.body = this.quill.root.innerHTML;
            this.$host
                .put(this.form.links.update, this.form)
                .then((res) => {
                    this.button.disabled = false;
                    this.form = res.data.data;
                    this.quill.root.innerHTML = this.form.body;
                    this.errors.message = "Notes was updated.";
                })
                .catch((err) => {
                    this.button.disabled = false;
                    if (err.response && err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },

        save($event) {
            this.button = $event.target;
            this.button.disabled = true;

            this.form.body = this.quill.root.innerHTML;

            this.$host
                .post("/api/notes", this.form)
                .then((res) => {
                    this.button.disabled = false;
                    this.errors = {};
                    this.quill.root.innerHTML = "";
                    this.$router.push({ name: "notes" });
                })
                .catch((err) => {
                    this.button.disabled = false;

                    if (err.response && err.response.status == 403) {
                        this.errors.message = "Don't you have rights.";
                    }
                    if (err.response && err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },

        listenEvents() {
            
            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("StoreTagEvent", (e) => {
                    this.getTags();
                });

            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("UpdateTagEvent", (e) => {
                    this.getTags();
                });

            this.$echo
                .private(this.$channels.ch_1(this.$id))
                .listen("DestroyTagEvent", (e) => {
                    this.getTags();
                });
        },
    },
};
</script>

<style scoped lang="scss">
.content-editor {
    padding-left: 1%;
    margin: auto;

    @media (min-width: 240px) {
        width: 100%;
    }

    @media (min-width: 850px) {
        width: 96%;
    }
}

#editor {
    height: 40vh;
}

.category {
    flex: 0 0 auto;

    @media (min-width: 240px) {
        width: 100%;
        padding: 3%;
    }

    @media (min-width: 850px) {
        width: 50%;
        padding: 1%;
    }
}
</style>
