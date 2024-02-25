import { createApp } from "vue";
import { $server, $host } from "./config/axios";
import { $echo, $channels } from "./config/echo";
import { router } from "./config/rutes";
import { components } from "./config/globalComponents";

import App from "./App.vue";
import Login from "./Login/Login.vue";

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
    if (to.meta.auth) {
        $server
            .get("/api/gateway/check-authentication")
            .then((res) => {
                /**
                 * if the user reload the page and is authenticated user
                 * we're redirec to to the home page
                 */
                if (window.location.pathname == "/login") {
                    window.location.href = process.env.MIX_APP_URL;
                } else {
                    /**
                     * If the user have a valid credentials go next request
                     */
                    next();
                }
            })
            .catch((err) => {
                /**
                 * Has a not valid crdential redirect to le login
                 */
                window.location.href = "/login";
            });
    } else {
        /**
         * if the route is no auth route pass next request
         */
        next();
    }
});

/**
 * Mountin Vue App
 */
$server
    .get("/api/gateway/user")
    .then((res) => {
        /**
         * Global user key from authenticated user
         */
        window.$id = res.data.id;

        /**
         * creating Vue App
         */
        const app = createApp(App);

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
    })
    .catch((err) => {
        /**
         * If the user is not authenticated redirect to the login
         * and mounting another Vue App
         */
        const app = createApp(Login);
        app.mount("#login");
    });
