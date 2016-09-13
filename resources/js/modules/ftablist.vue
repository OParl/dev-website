<template>
    <div class="c-tablist">
        <ul>
            <li v-for="tab in tabs">{{ tab.title }}</li>
        </ul>

        <slot></slot>
    </div>

</template>

<script>
    import FTab from './ftab.vue'
    import FModal from './fmodal.vue'

    export default {
        data() {
            return {
                tabs: [],
                isActive: '',
            }
        },

        components: {
            FTab,
            FModal
        },

        events: {
            'f-tablist:new-tab': function (title) {
                this.tabs.push({ title: title })
            }
        }
    }
</script>