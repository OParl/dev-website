<template>
    <div class="c-accordion">
        <div class="c-accordion--header" @click="show = !show" v-if="!hasTrigger">
            <i class="fa {{ caret }}"></i>
            <slot name="title"></slot>
        </div>
        <div class="c-accordion--body" v-show="show" transition="expand">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                required: false,
                twoWay: true,
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
            },
        },
    };
</script>