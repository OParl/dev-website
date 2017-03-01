import Vue from 'vue/dist/vue.js'
import './directives.js'

import Helpers from './modules/helpers.js'
import FModal from './modules/fmodal.vue'
import FAccordion from './modules/faccordion.vue'

var vm = new Vue({
    el: '#app',

    data: {
        showDownloadsModal: false,
        showTableOfContents: false,
    },

    created() {
        //Helpers.prismURLHelper();
    },

    components: {
        FModal,
        FAccordion
    }
});

window.vm = vm;
