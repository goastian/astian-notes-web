<template>
    <!-- crear etiquetas-->
    <div class="row" v-show="!update">
        <p class="m-2">Nueva tag</p>
        <div class="col m-2">
            <input
                type="text"
                class="form-control"
                placeholder="Nueva Etiqueta"
                v-model="form.tag"
                @keypress.enter="save"
            />
            <p class="errors" v-for="(item, index) in errors.tag" :key="index">
                {{ item }}
            </p>
        </div>
        <div class="col m-2">
            <button class="btn btn-primary" @click="save">
                Crear nueva etiqueta
            </button>
        </div>
    </div>
    <!--fin-->

    <!-- actualizar-->
    <div class="row" v-show="update">
        <p class="m-2">Actualizar etiqueta</p>
        <div class="col m-2">
            <input
                type="text"
                class="form-control"
                placeholder="Nueva Etiqueta"
                v-model="tag.tag"
                @keypress.enter="upgrade(this.tag.links.update)"
            />
            <p class="errors" v-for="(item, index) in errors.tag" :key="index">
                {{ item }}
            </p>
        </div>
        <div class="col m-2">
            <button
                class="btn btn-ternary"
                @click="upgrade(this.tag.links.update)"
            >
                Actuaizar
            </button>
        </div>
    </div>

    <!--fin-->

    <!--Mostrar etiquetas-->
    <div class="tag-list mt-3">
        <p class="mx-2">
            <i class="bi bi-tags"></i>
            Etiquetas
        </p>
        <ul class="tag-list-item">
            <li v-for="(item, index) in tags" :key="index">
                <button
                    class="btn btn-secondary"
                    @click="show(item.links.show)"
                >
                    <i class="bi bi-cloud-arrow-up-fill"></i>
                </button>
                <a class="btn btn-link text-light">
                    {{ item.tag }}
                </a>
                <button
                    class="btn btn-secondary"
                    @click="remove(item.links.destroy)"
                >
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
            form: {}, //data para registrar
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
        save() {
            this.$host
                .post("/api/tags", this.form)
                .then((res) => {
                    this.errors = {};
                    this.form.tag = null;
                    this.getTags();
                })
                .catch((err) => {
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
         * @param {*} link
         */
        upgrade(link) {
            this.$host
                .put(link, this.tag)
                .then((res) => {
                    this.getTags();
                    this.update = false;
                })
                .catch((err) => {
                    if (err.response) {
                        this.errors.message = err.response.data.message;
                    }
                });
        },

        /**
         * Remove one tag
         * @param {*} link
         */
        remove(link) {
            this.$host
                .delete(link)
                .then((res) => {
                    this.getTags();
                })
                .catch((err) => {
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
    background-color: var(--secondary);
    color: var(--white);
    border-radius: 2%;
    margin-right: 1%;
    margin-top: 1%;
}
</style>
