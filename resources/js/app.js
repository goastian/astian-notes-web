import { createApp } from "vue";
import { $server, $host } from "./config/axios";
import { $echo, $channels } from "./config/echo";
import { router } from "./config/rutes";
import { components } from "./config/globalComponents";

import App from "./App.vue";
import Login from "./Login/Login.vue";

//bootstrap
import * as bootstrap from "bootstrap";

//Starting App
$server
    .get("/api/gateway/user")
    .then((res) => {
        //Start authenticable user
        window.$auth = res.data;

        if (window.location.pathname == "/login") {
            window.location.href = process.env.MIX_APP_URL;
        }

        //init app
        const app = createApp(App);

        //global properties
        app.config.globalProperties.$server = $server;
        app.config.globalProperties.$host = $host;
        app.config.globalProperties.$echo = $echo;
        app.config.globalProperties.$channels = $channels;

        //global components
        components.forEach((index) => {
            app.component(index[0], index[1]);
        });

        //use vue router
        app.use(router);

        app.mount("#app");
    })
    .catch((err) => {
        //Unauthenticable APP
        const app = createApp(Login);
        app.mount("#login");
    });
