<template>
    <aside class="side bg-light pt-4">
        <ul>
            <li>
                <i class="bi bi-journal-bookmark m-3"></i>
                <router-link
                    class="text-ligth"
                    :to="{ name: 'notes.create' }"
                    @click="isClicked()"
                >
                    Notes
                </router-link>
            </li>
            <li class="mt-2">
                <i class="bi bi-bookmarks-fill m-3"></i>
                <router-link
                    class="text-ligth"
                    :to="{ name: 'tag.create' }"
                    @click="isClicked"
                >
                    Tags
                </router-link>
            </li>
        </ul>
        <ul>
            <li>
                <i class="bi bi-list"></i>
                <span class="fw-bold text-color">Menu</span>
                <ul>
                    <li>
                        <router-link :to="{ name: 'notes' }" @click="isClicked">
                            <i class="bi bi-bookmarks-fill"></i> All
                        </router-link>
                    </li>
                    <li class="mt-2" v-for="(item, index) in tags" :key="index">
                        <router-link
                            :to="{
                                name: 'notes',
                                params: {
                                    id: item.id,
                                },
                            }"
                            @click="isClicked"
                        >
                            <i class="bi bi-tag"></i>{{ item.tag }}
                        </router-link>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</template>
<script>
export default {
    emits: ["selectedMenu"],

    data() {
        return {
            tags: {},
            app_name: process.env.MIX_APP_NAME,
        };
    },

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
        this.getTags();
        this.listenEvents();
    },

    methods: {
        screenIsChanging() {
            if (window.innerWidth < 940) {
                this.$emit("selectedMenu", window.innerWidth < 940);
            }
        },

        getTags() {
            this.$host
                .get("/api/tags", { params: { per_page: 500 } })
                .then((res) => {
                    this.tags = res.data.data;
                })
                .catch((err) => {});
        },

        search(id) {
            this.$router.push({
                name: "notes",
                params: {
                    id: id,
                },
            });

            this.isClicked();
        },

        isClicked() {
            if (window.innerWidth < 940) {
                this.$emit("selectedMenu", window.innerWidth < 940);
            }
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
.side {
    padding-top: 3%;
    height: 100vh;
    color: var(--dark);
}

.side ul:nth-child(1) {
    padding-left: 1%;
    list-style: none;
    border-bottom: 2px solid var(--ternary);
    padding-bottom: 4%;
}

.side ul:nth-child(2) {
    list-style: none;
    padding-left: 5%;
}
.side ul:nth-child(2) ul {
    list-style: none;
    padding-left: 8%;
}

.side a {
    color: var(--code);
}
</style>
