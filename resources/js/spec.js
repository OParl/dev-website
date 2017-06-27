let Prism = require('!!prismjs?lang=javascript');
import Vue from 'vue'

import FModal from './modules/fmodal.vue'
import FAccordion from './modules/faccordion.vue'

const vm = new Vue({
    el: '#app',

    data: {
        showDownloadsModal: false,
        showTableOfContents: false,
    },

    mounted() {
        Prism.highlightAll();
    },

    components: {
        FModal,
        FAccordion
    }
});

window.vm = vm;
