<template>
    <div class="wrapper">
        <div class="content">
            <v-home v-if="authenticated"></v-home>

            <div v-else-if="!authenticated">
                <router-view></router-view>
            </div>
        </div>
        <v-footer></v-footer>
    </div>
</template>
<script>
import VHome from "./dashboard/Index.vue";
import VLogin from "./Login/Login.vue";
import VFooter from "./dashboard/Footer.vue";

export default {
    components: {
        VHome,
        VLogin,
        VFooter,
    },

    computed: {
        authenticated() {
            return this.$route.meta.auth || false;
        },
    },
};
</script>
<style lang="scss">
.wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.content {
    flex: 1;
    min-height: 92vh;
}
</style>
