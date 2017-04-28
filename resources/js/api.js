import Vue from 'vue/dist/vue.common'

import FAccordion from './modules/faccordion.vue'

const vm = new Vue({
    el: '#app',

    created() {
        //Prism.highlightAll();
    },

    components: {
        FAccordion
    },
});

window.vm = vm;
