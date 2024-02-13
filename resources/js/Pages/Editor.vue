<template>
    <div class="row content">
        <div class="category">
            <input
                type="text"
                v-model="form.titulo"
                class="form-control"
                placeholder="Title"
            />
            <span
                class="errors"
                v-for="(item, index) in errors.titulo"
                :key="index"
                v-text="item"
            ></span>
        </div>
        <div class="category">
            <select class="form-control" v-model="form.etiqueta_id">
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
                v-for="(item, index) in errors.cuerpo"
                :key="index"
                v-text="item"
            ></span>

            <button
                v-show="!update"
                class="btn btn-secondary mt-3"
                @click="save"
            >
                Save note <i class="bi bi-floppy-fill mx-2"></i>
            </button>
            <button
                v-show="update"
                class="btn btn-primary mt-3"
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
                cuerpo: null,
            },
            tags: {},
        };
    },

    created() {
        this.getTags();
        this.listenEvents();
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
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        getNote(id) {
            this.$host
                .get("/api/notes/" + id)
                .then((res) => {
                    this.form = res.data.data;
                    this.quill.root.innerHTML = this.form.cuerpo;
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        updateNote() {
            this.errors.message = "";
            this.form.cuerpo = this.quill.root.innerHTML;
            this.$host
                .put(this.form.links.update, this.form)
                .then((res) => {
                    this.form = res.data.data;
                    this.quill.root.innerHTML = this.form.cuerpo;
                    this.errors.message = "Nota actualizada";
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        save() {
            this.form.cuerpo = this.quill.root.innerHTML;
            this.$host
                .post("/api/notes", this.form)
                .then((res) => {
                    this.$router.push({ name: "notes" });
                    this.errors = {};
                })
                .catch((err) => {
                    if (err.response && err.response.status == 403) {
                        this.errors.message =
                            "no cuenta con los permisos necesarios";
                    }
                    if (err.response && err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("StoreTagEvent", (e) => {
                    this.getTags();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("UpdateTagEvent", (e) => {
                    this.getTags();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyTagEvent", (e) => {
                    this.getTags();
                });
        },
    },
};
</script>

<style scoped lang="scss">
.content {
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

select,
input {
    border: 1px solid var(--quaternary);
}
</style>
