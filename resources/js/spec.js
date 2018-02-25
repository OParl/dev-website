let Prism = require('!!prismjs?lang=javascript');
import Vue from './vue-base'

import FAccordion from './modules/faccordion.vue'
import SpecificationExample from './modules/specification-example'

const vm = new Vue({
    el: '#app',

    mounted() {
        Prism.highlightAll();
    },

    components: {
        FAccordion,
        SpecificationExample
    }
});

window.vm = vm;
