<template>
    <!-- crear etiquetas-->
    <div class="row text-color">
        <p class="m-2"></p>
        <div class="col m-2">
            <input
                type="text"
                class="form-control"
                placeholder="Tag name"
                v-model="tag.tag"
            />
            <p class="errors" v-for="(item, index) in errors.tag" :key="index">
                {{ item }}
            </p>
        </div>
        <div class="col m-2">
            <button class="btn btn-primary" @click="save" v-show="!update">
                save <i class="bi bi-floppy-fill mx-2"></i>
            </button>

            <button class="btn btn-ternary" @click="upgrade" v-show="update">
                Update <i class="bi bi-cloud-upload mx-2"></i>
            </button>
        </div>
    </div>

    <!--Mostrar etiquetas-->
    <div class="tag-list mt-3">
        <p class="mx-2 fw-bold text-color">
            <i class="bi bi-tags"></i>
            Tags
        </p>
        <ul class="tag-list-item">
            <li
                class="bg-primary text-color px-1 py-1"
                v-for="(item, index) in tags"
                :key="index"
            >
                <button class="btn" @click="show(item.links.show)">
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                </button>

                {{ item.tag }}

                <button class="btn" @click="remove(item.links.destroy, $event)">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </li>
        </ul>
        <!--fin-->
        <strong class="errors" v-show="errors.message">{{
            errors.message
        }}</strong>
    </div>
</template>

<script>
export default {
    data() {
        return {
            update: false, //intercala entre el formulario para crear y actualizar
            tags: {}, //almacena todas las etiquetas
            tag: {}, //almacena los valores de una tag
            errors: {
                //errores
                message: null,
            },
            button: {},
        };
    },

    created() {
        this.getTags();
        this.listenEvents();
    },

    methods: {
        /**
         * save new tags in the database
         */
        save(event) {
            this.button = event.target;
            this.button.disabled = true;

            this.$host
                .post("/api/tags", this.tag)
                .then((res) => {
                    this.button.disabled = false;
                    this.errors = {};
                    this.tag = {};
                    //this.getTags();
                })
                .catch((err) => {
                    this.button.disabled = false;

                    if (err.response && err.response.status == 403) {
                        this.errors.message = err.response.data.message;
                    }
                    if (err.response && err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }
                });
        },

        /**
         * Show one tag
         * @param {*} link
         */
        show(link) {
            this.update = true;

            this.$host
                .get(link)
                .then((res) => {
                    this.tag = res.data.data;
                })
                .catch((err) => {
                    if (err.response) {
                        this.errors.message = err.response.data.message;
                    }
                });
        },

        /**
         * Update one tag
         */
        upgrade(event) {
            this.button = event.target;
            this.button.disabled = true;

            this.$host
                .put(this.tag.links.update, this.tag)
                .then((res) => {
                    //  this.getTags();
                    this.update = false;
                    this.button.disabled = false;
                    this.tag = {};
                })
                .catch((err) => {
                    this.button.disabled = false;
                    if (err.response) {
                        this.errors.message = err.response.data.message;
                    }
                });
        },

        /**
         * Remove one tag
         * @param {*} link
         */
        remove(link, event) {
            this.button = event.target;
            this.button.disabled = true;

            this.$host
                .delete(link)
                .then((res) => {
                    this.button.disabled = false;
                    // this.getTags();
                })
                .catch((err) => {
                    this.button.disabled = false;
                    if (err.response) {
                        this.errors.message = err.response.data.message;
                    }
                });
        },

        /**
         * retrieve the all tags
         */
        getTags() {
            this.$host
                .get("/api/tags", { params: { per_page: 50 } })
                .then((res) => {
                    this.tags = res.data.data;
                })
                .catch((err) => {
                    if (err.response) {
                        this.errors.message = err.response.data.message;
                    }
                });
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_1(window.$auth.id))
                .listen("StoreTagEvent", (e) => {
                    this.getTags();
                });

            this.$echo
                .private(this.$channels.ch_1(window.$auth.id))
                .listen("UpdateTagEvent", (e) => {
                    this.getTags();
                });

            this.$echo
                .private(this.$channels.ch_1(window.$auth.id))
                .listen("DestroyTagEvent", (e) => {
                    this.getTags();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.col {
    flex: 0 0 auto;

    @media (min-width: 240px) {
        width: 96%;
    }

    @media (min-width: 940px) {
        width: 48%;
    }
}
.tag-list p {
    border-bottom: 1px solid var(--ternary);
}

.tag-list-item {
    list-style: none;
    @media (min-width: 240px) {
        padding-left: 2%;
    }

    @media (min-width: 940px) {
        padding-left: 2rem;
    }
}

.tag-list-item li {
    display: inline-block;
    border-radius: 2%;
    margin-right: 1%;
    margin-top: 1%;
}
</style>
