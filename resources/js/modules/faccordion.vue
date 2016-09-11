<template>
    <div class="c-accordion">
        <div class="c-accordion--header" @click="show = !show">
            <i class="fa {{ caret }}"></i>
            <slot name="title"></slot>
        </div>
        <div class="c-accordion--body" v-show="show" transition="expand">
            <slot name="content"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                show: false
            }
        },

        computed: {
            caret() {
                return (this.show) ? 'fa-caret-down' : 'fa-caret-right';
            },
        },
    };
</script>