import Vue from 'vue'
import VueResource from 'vue-resource'
import Helpers from './modules/helpers.js'

import FTabList from './modules/ftablist.vue'
import FTab from './modules/ftab.vue'

Vue.use(VueResource);

var vm = new Vue({
    el: 'body',

    created() {
        Helpers.prismURLHelper();
    },

    components: {
        FTabList,
        FTab
    },

    events: {
        'f-tab:activate': function(ident) {
            Prism.highlightAll();
        }
    }
});

window.vm = vm;
