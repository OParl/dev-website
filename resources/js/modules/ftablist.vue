<template>
    <div class="c-tablist">
        <nav>
            <ul>
                <li v-for="tab in tabs" @click="notify(tab.ident)"
                    aria-controls="#{{tab.ident}}"
                    class="{{ activeClass(tab.ident) }}">{{ tab.title }}</li>
            </ul>
        </nav>

        <section>
            <slot></slot>
        </section>
    </div>

</template>

<script>
    import FTab from './ftab.vue'

    export default {
        data() {
            return {
                tabs: [],
                isActive: '',
            }
        },

        components: {
            FTab
        },

        events: {
            'f-tablist:new-tab': function (tabInfo) {
                this.tabs.push(tabInfo);

                if (this.tabs.length == 1) {
                    this.isActive = tabInfo.ident;
                    this.notify(tabInfo.ident);
                }
            }
        },

        methods: {
            notify(ident)  {
                this.$broadcast('f-tab:activate', ident);
                this.$dispatch('f-tab:activate', ident);
            },
            activeClass(tab) {
                return this.isActive === tab.ident ? 'active' : '';
            }
        }
    }
</script>