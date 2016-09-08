import Vue from 'vue'
import Helpers from './modules/helpers.js'
import FModal from './modules/fmodal.vue'

var vm = new Vue({
    el: 'body',

    data: {
        showDownloadsModal: false
    },

    methods: {
    },

    created() {
        Helpers.prismURLHelper();
    },

    components: {
        FModal
    }
});

window.vm = vm;
