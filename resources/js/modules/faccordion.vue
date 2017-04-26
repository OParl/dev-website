<template>
    <div class="c-accordion">
        <div class="c-accordion--header" @click="isShowing = !isShowing" v-if="!hasTrigger">
            <slot name="title"></slot>
            <i class="fa" :class="caret"></i>
        </div>
        <transition name="expand">
            <div class="c-accordion--body" v-show="isShowing">
                <slot name="body"></slot>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: 'FAccordion',

        data() {
            return {
                isShowing: this.show
            }
        },

        props: {
            show: {
                type: Boolean,
                required: false,
                default: false
            },

            hasTrigger: {
                type: Boolean,
                required: false
            }
        },

        computed: {
            caret() {
                return (this.isShowing) ? 'fa-caret-down' : 'fa-caret-right';
            }
        }
    };
</script>