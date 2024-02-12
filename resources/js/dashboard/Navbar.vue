<template>
    <ul class="nav bg-primary pt-2">
        <li class="nav-item" @click="Expand(status)">
            <span class="mx-2">
                {{ app_name }}
            </span>
            <a href="#" class="btn btn-primary"
                ><i class="bi bi-list h5"></i
            ></a>
        </li>
        <li class="nav-item ms-auto">
            <v-apps></v-apps>
        </li>

        <li class="nav-item dropdown">
            <a
                class="btn btn-primary dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="true"
            >
                <i class="bi bi-bell-fill h5" @click="unreadNotification"></i>
                <span class="position-absolute badge rounded-pill bg-danger">
                    {{ unread_notifications.length }}
                    <span class="visually-hidden">unread messages</span>
                </span>
            </a>
            <ul class="dropdown-menu bg-secondary">
                <li class="dropdown-item h5">
                    <a :href="host + '/notifications/unread'">
                        Notifications
                        <span class="badge text-bg-danger">{{
                            unread_notifications.length
                        }}</span>
                    </a>
                </li>
                <li class="dropdown-divider"></li>
                <li
                    class="dropdown-item p-0"
                    v-for="(item, index) in unread_notifications"
                    :key="index"
                >
                    <a
                        :href="item.recurso"
                        target="_blank"
                        @click="readNotification(item.links.read)"
                    >
                        <strong class=""
                            >{{ item.titulo }}
                            <i
                                :class="[
                                    'bi h5 mx-2',
                                    item.leido ? 'bi-eye' : 'bi-eye-slash',
                                ]"
                            ></i>
                        </strong>
                    </a>
                </li>
            </ul>
        </li>

        <li class="nav-item dropdown icon">
            <a
                class="btn btn-primary dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="true"
            >
                {{ user.nombre }}
                <i class="bi bi-box-arrow-in-right h4 m-0"></i>
            </a>
            <ul class="dropdown-menu bg-ternary">
                <li class="dropdown-item p-0">
                    <a class="btn btn-link" @click="logout">
                        <i class="bi bi-lock-fill mx-2"></i>
                        Cerrar session
                    </a>
                </li>
                <li class="dropdown-item dropdown-devider"></li>
            </ul>
        </li>
    </ul>
</template>
<script>
import VApps from "./Apps.vue";

export default {
    emits: ["expand"],

    props: ["status"],

    components: {
        VApps,
    },

    data() {
        return {
            expand: false,
            notifications: {},
            unread_notifications: {},
            host: process.env.MIX_APP_SERVER,
            app_name: process.env.MIX_APP_NAME,
            user: {},
        };
    },

    mounted() {
        window.addEventListener("resize", this.screenIsChanging);
        this.screenIsChanging();
        this.notification();
        this.auth();
        this.unreadNotification();
        this.listenEvents();
    },

    methods: {
        Expand(status = false) {
            this.expand = !status;
            this.$emit("expand", this.expand);
        },

        screenIsChanging() {
            this.expand = window.innerWidth < 940;
        },

        auth() {
            this.$server
                .get("/api/gateway/user")
                .then((res) => {
                    this.user = res.data;
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response.data);
                    }
                });
        },

        logout() {
            this.$server
                .post("api/gateway/logout")
                .then((res) => {
                    window.location.href = process.env.APP_URL;
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        notification() {
            this.$server
                .get("api/notifications")
                .then((res) => {
                    this.notifications = res.data.data;
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        unreadNotification() {
            this.$server
                .get("api/notifications/unread")
                .then((res) => {
                    this.unread_notifications = res.data.data;
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        readNotification(link) {
            this.$server
                .post(link)
                .then((res) => {
                    this.notification();
                })
                .catch((err) => {
                    if (err.response) {
                        console.log(err.response);
                    }
                });
        },

        listenEvents() {
            this.$echo
                .private(this.$channels.ch_0())
                .listen("PushNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("ReadNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });

            this.$echo
                .private(this.$channels.ch_0())
                .listen("DestroyNotificationEvent", (e) => {
                    this.notification();
                    this.unreadNotification();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.dropdown-item a {
    text-decoration: none;
    color: var(--dark);
}

.dropdown-item {
    margin: 0 1%;
}

.nav-item {
    margin-right: 2%;
}
</style>
