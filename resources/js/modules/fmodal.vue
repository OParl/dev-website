<template>
    <div class="modal-mask" v-show="show" transition="modal">
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
                                @click="show = false">
                            {{ text.okBtn }}
                        </button>
                    </slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                required: true,
                twoWay: true
            }
        },
        data() {
            return {
                text: {
                    okBtn: 'OK'
                }
            }
        }
    };
</script>
