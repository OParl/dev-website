import Vue from 'vue'
import VueResource from 'vue-resource'

Vue.use(VueResource)

import FDropList from './modules/fdroplist.vue'
import FSelect from './modules/fselect.vue'

var vm = new Vue({
    el: '#spec',
    components: {
        FSelect: FSelect,
        FDropList: FDropList
    }
});

window.vm = vm;
