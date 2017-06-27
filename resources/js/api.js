let Prism = require('!!prismjs?lang=javascript');
import Vue from 'vue'

import FAccordion from './modules/faccordion.vue'

const vm = new Vue({
    el: '#app',

    mounted() {
        Prism.highlightAll();
    },

    components: {
        FAccordion
    },
});

window.vm = vm;
