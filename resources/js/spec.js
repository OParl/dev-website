let Prism = require('!!prismjs?lang=javascript');
import Vue from 'vue'

import FModal from './modules/fmodal.vue'
import FAccordion from './modules/faccordion.vue'

import SpecificationExample from './modules/specification-example.vue'

const vm = new Vue({
    el: '#app',

    data: {
        showDownloadsModal: false,
    },

    mounted() {
        Prism.highlightAll();
    },

    methods: {
        toggleTableOfContents() {
            this.$refs.tableOfContents.toggleShow()
        }
    },

    components: {
        FModal,
        FAccordion,
        SpecificationExample
    }
});

window.vm = vm;
