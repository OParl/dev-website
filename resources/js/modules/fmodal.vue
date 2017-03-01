<template>
    <transition name="modal">
        <div class="modal-mask" v-show="show">
            <div class="modal-wrapper" @click.self="show = false">
                <div class="modal-container">

                    <div class="modal-header">
                        <slot name="header">
                        </slot>
                    </div>

                    <div class="modal-body">
                        <slot name="body">
                        </slot>
                    </div>

                    <div class="modal-footer">
                        <slot name="footer">
                            <button class="modal-default-button"
                                    @click="confirm">
                                {{ text.okBtn }}
                            </button>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                required: true
            }
        },
        data() {
            return {
                text: {
                    okBtn: 'OK'
                }
            }
        },
        methods: {
            confirm: function() {
                this.show = false;
                $emit('confirm');
            }
        }
    };
</script>
