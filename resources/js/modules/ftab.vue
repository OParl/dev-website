<template>
    <div class="c-tab" v-if="isActive">
        <slot></slot>
    </div>
</template>
<script>
    export default {
        props: {
            title: {
                required: true,
                type: String,
            },

            isActive: {
                required: true,
                type: Boolean
            }
        },

        ready() {
            this.$dispatch('f-tablist:new-tab', this.title)
        }
    }
</script>