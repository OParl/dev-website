<template>
    <div class="c-accordion">
        <div class="c-accordion--header" @click="show = !show" v-if="!hasTrigger">
            <slot name="title"></slot>
            <i class="fa" :class="caret"></i>
        </div>
        <transition name="expand">
            <div class="c-accordion--body" v-show="show">
                <slot name="body"></slot>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: 'FAccordion',

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
                return (this.show) ? 'fa-caret-down' : 'fa-caret-right';
            }
        }
    };
</script>