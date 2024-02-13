<template>
    <div class="row m-0">
        <v-nav @expand="taggleLefbar" :status="toggle_lef_bar"></v-nav>
        <div class="left px-0" v-show="!toggle_lef_bar">
            <v-left-bar @selected-menu="taggleLefbar"></v-left-bar>
        </div>
        <div :class="['p-0', [toggle_lef_bar ? 'body-expand' : 'body']]">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import VNav from "./dashboard/Navbar.vue";
import VLeftBar from "./dashboard/Leftbar.vue";

export default {
    components: {
        VNav,
        VLeftBar,
    },

    data() {
        return {
            toggle_lef_bar: false,
        };
    },

    methods: {
        taggleLefbar(event) {
            this.toggle_lef_bar = event;
        },
    },
};
</script>

<style scoped lang="scss">
.left {
    flex: 0 0 auto;
    overflow-y: scroll;

    @media (min-width: 240px) {
        width: 100%;
    }

    @media (min-width: 850px) {
        width: 22%;
        height: 100vh;
    }
}

.body {
    flex: 0 0 auto;
    height: 100vh;
    overflow-y: scroll;

    @media (min-width: 240px) {
        width: 100%;
    }

    @media (min-width: 800px) {
        width: 78%;
    }
}

.body-expand {
    flex: 0 0 auto;
    width: 100%;
    height: 100vh;
    overflow-y: scroll;
}
</style>
