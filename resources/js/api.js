import Vue from 'vue'
import Helpers from './modules/helpers.js'

import FAccordion from './modules/faccordion.vue'

var vm = new Vue({
    el: 'body',

    components: [
        FAccordion
    ],

    created() {
        Helpers.prismURLHelper();
    },
});

window.vm = vm;
