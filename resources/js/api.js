import Vue from 'vue/dist/vue.js'
import Helpers from './modules/helpers.js'

import FAccordion from './modules/faccordion.vue'
import OparlClient from './modules/oparl-client.vue'

// TODO: Prism.js + javascript highlighting + line numbers plugin
import Prism from 'prismjs';
window.Prism = Prism;

let vm = new Vue({
    el: '#app',

    components: [
        FAccordion,
        OparlClient
    ],

    created() {
        //Helpers.prismURLHelper();
        Prism.highlight();
    },
});

window.vm = vm;
