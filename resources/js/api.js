import Vue from 'vue'
import VueResource from 'vue-resource'
import Helpers from './modules/helpers.js'
import FApiConsole from './modules/fapiconsole.vue'

Vue.use(VueResource);

var vm = new Vue({
    el: 'body',

    created() {
        Helpers.prismURLHelper();
    },

    components: {
        FApiConsole: FApiConsole
    }
});

window.vm = vm;
