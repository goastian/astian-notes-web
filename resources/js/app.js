import { createApp } from "vue";
import { $server, $host } from "./config/axios";
import { $echo, $channels } from "./config/echo";
import { router } from "./config/rutes";
import { components } from "./config/globalComponents";

import App from "./App.vue";
//bootstrap
import * as bootstrap from "bootstrap";
import "./config/matomo";

/**
 * Cheking Routes from VueRouter
 */
router.beforeEach((to, from, next) => {
    /**
     * checking for valid credentials
     */
    $server
        .get("/api/gateway/check-authentication")
        .then((res) => {
            /**
             * Checking if the route is auth
             */
            if (to.meta.auth) {
                next();
            } else if (!to.meta.auth && to.name == "login") {
                /**
                 * Ckecking the user is auth and the route is
                 * login we're redirect to the user to notes route
                 */
                return next({ name: "notes" });
            }
        })
        .catch((err) => {
            /**
             * Has a not valid crdential redirect to le login
             */
            if (to.meta.auth) {
                //redirect to the login if the route is auth
                next({ name: "login" });
            } else {
                next();
            }
        });
});

/**
 * creating Vue App
 */
const app = createApp(App);

/**
 * Global user key from authenticated user
 */
$server
    .get("/api/gateway/user")
    .then((res) => {
        app.config.globalProperties.$id = res.data.id;
    })
    .catch((err) => {});
/**
 * Global properties for Vuejs
 */
app.config.globalProperties.$server = $server;
app.config.globalProperties.$host = $host;
app.config.globalProperties.$echo = $echo;
app.config.globalProperties.$channels = $channels;

/**
 * Global Components for Vuejs
 */
components.forEach((index) => {
    app.component(index[0], index[1]);
});

/**
 * Routes from vueRoute
 */
app.use(router);

/**
 * Mounting App
 */
app.mount("#app");
