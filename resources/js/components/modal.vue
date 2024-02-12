<template>
    <button
        :class="btn_primary"
        @click="sendEvent1(item)"
        data-bs-toggle="modal"
        :data-bs-target="'#'.concat(target)"
    >
        <slot name="button"></slot>
    </button>

    <!-- Modal -->
    <div
        class="modal fade"
        :id="target"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <slot name="head"></slot>
                </div>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div class="modal-footer">
                    <button
                        data-bs-dismiss="modal"
                        :class="btn_accept"
                        @click="sendEvent2(item)"
                    >
                        Aceptar
                    </button>
                    <button
                        :class="btn_cancel"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        @click="sendEvent3(item)"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    emits: ["isClicked", "isAccepted", "isClosed"],

    props: {
        item: { required: true, type: [String, Object] },
        target: { required: true },
        btn_primary: { required: false, default: "btn" },
        btn_accept: { required: false, default: "btn btn-success" },
        btn_cancel: { required: false, default: "btn btn-danger" },
    },

    methods: {
        sendEvent1(event) {
            this.$emit("isClicked", event);
        },

        sendEvent2(event) {
            this.$emit("isAccepted", event);
        },

        sendEvent3(event) {
            this.$emit("isClosed", event);
        },
    },
};
</script>
