import Vue from 'vue'
import VueResource from 'vue-resource'

import FSelect from './modules/fselect.vue'

import Helpers from './modules/helpers.js'

Vue.use(VueResource);

var vm = new Vue({
    el: 'body',

    created() {
        Helpers.prismURLHelper();
    },

    components: {
        FSelect: FSelect
    }
});

window.vm = vm;
