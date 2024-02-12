import axios from "axios";
import Cookies from "js-cookie";

export const $server = axios.create({
    baseURL: process.env.MIX_APP_SERVER,
    timeout: 5000,
    withCredentials: true,
    headers: { 
        Accept: "application/json",
        Authorization: "Bearer " + Cookies.get(process.env.MIX_ECHO_TOKEN),
    },
});

export const $host = axios.create({
    baseURL: process.env.MIX_APP_URL,
    timeout: 5000,
    withCredentials: true,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
        Authorization: Cookies.get(process.env.MIX_ECHO_TOKEN),
    },
});
