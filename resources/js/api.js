import Vue from 'vue/dist/vue.js'
import Helpers from './modules/helpers.js'

import FAccordion from './modules/faccordion.vue'

var vm = new Vue({
    el: '#app',

    components: [
        FAccordion
    ],

    created() {
        Helpers.prismURLHelper();
    },
});

window.vm = vm;
