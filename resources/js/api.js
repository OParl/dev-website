import Vue from 'vue/dist/vue.common'

import FModal from './modules/fmodal.vue'
import FAccordion from './modules/faccordion.vue'

const vm = new Vue({
    el: '#app',

    data: {
        showDownloadsModal: false,
        showTableOfContents: false,
    },

    created() {
        Prism.highlightAll();
    },

    components: {
        FModal,
        FAccordion
    },
});

window.vm = vm;
