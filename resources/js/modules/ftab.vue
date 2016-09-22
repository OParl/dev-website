<template>
    <div class="c-tab" id="{{ ident }}">
        <slot v-if="isActive" keep-alive></slot>
    </div>
</template>

<script>
    export default {
        props: {
            title: {
                required: true,
                type: String,
            }
        },

        data() {
            return {
                isActive: false,
                ident: ''
            }
        },

        ready() {
            // TODO: make this generate a unique id
            this.ident = this.title;

            this.$dispatch('f-tablist:new-tab', {
                ident: this.ident,
                title: this.title
            })
        },

        events: {
            'f-tab:activate': function(ident) {
                this.isActive = (this.ident === ident);
            }
        }
    }
</script>