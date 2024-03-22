<template>
    <footer class="footer">
        <ul>
            <li class="footer-hover">
                <a :href="policy" target="_blank">Privacy Policy</a>
            </li>
            <li class="footer-hover">
                <a :href="developers" target="_blank">Developers</a>
            </li>
            <li class="footer-hover">
                <a :href="terms" target="_blank">Terms of Service</a>
            </li>
            <li class="footer-hover">
                <a :href="contact" target="_blank">Contact Us</a>
            </li>
        </ul>

        <ul>
            <li>
                Copyright ©
                {{ time }}
                -
                <strong> {{ app_name }} </strong>, All Rights Reserved
            </li>
        </ul>
    </footer>
</template>
<script>
export default {
    data() {
        return {
            app_name: process.env.MIX_APP_NAME,
            policy: process.env.MIX_HOME_POLICY,
            developers: process.env.MIX_HOME_DEVELOPER,
            terms: process.env.MIX_HOME_TERMS,
            contact: process.env.MIX_HOME_CONTACT,
            time: new Date().getFullYear(),
        };
    },
};
</script>
<style lang="scss">
.footer {
    font-size: 0.8em;
    min-height: 5vh;
    margin: 0%;
    background-color: var(--footer-bg);
    color: var(--footer-color);
}

.footer ul {
    display: block !important;
    margin: 0;
    padding: 0;
    text-align: center;
}

.footer ul:first-child li {
    display: inline-block;
    margin: 0.5% 2%;
}

.footer ul:first-child li a {
    color: inherit;
}

.footer ul:last-child li {
    display: inline;
}
</style>
