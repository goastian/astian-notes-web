<template>
    <div class="row p-0 m-0">
        <v-nav @expand="taggleLefbar" :status="toggle_left_bar"></v-nav>
        <div class="left" v-show="!toggle_left_bar">
            <v-left-bar @selected-menu="taggleLefbar"></v-left-bar>
        </div>
        <div
            :class="{
                'content-body': true,
                'body-expand': toggle_left_bar,
                body: !toggle_left_bar,
            }"
        >
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import VNav from "./Navbar.vue";
import VLeftBar from "./Leftbar.vue";

export default {
    components: {
        VNav,
        VLeftBar,
    },

    data() {
        return {
            toggle_left_bar: false,
        };
    },

    methods: {
        taggleLefbar(event) {
            this.toggle_left_bar = event;
        },
    },
};
</script>

<style scoped lang="scss">
.left {
    overflow-y: scroll;
    padding: 0;
    margin: 0;

    @media (min-width: 240px) {
        width: 100%;
    }

    @media (min-width: 850px) {
        width: 20%;
        height: 100vh;
    }
}

.content-body {
    min-height: 100vh;
    margin: 0;
    padding: 0;
    overflow-y: auto;
}

.body {
    @media (min-width: 240px) {
        width: 100%;
    }

    @media (min-width: 800px) {
        width: 80%;
    }
}

.body-expand {
    width: 100%;
}
</style>
