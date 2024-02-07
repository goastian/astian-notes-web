import { createApp } from "vue";
import { $server, $host } from "./config/axios";
import { $echo, $channels } from "./config/echo";

import App from "./App.vue";
import * as bootstrap from "bootstrap";

const app = createApp(App);

app.config.globalProperties.$server = $server
app.config.globalProperties.$host = $host;
app.config.globalProperties.$echo = $echo
app.config.globalProperties.$channels = $channels;

app.mount("#app");
