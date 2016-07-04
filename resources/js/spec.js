import Vue from 'vue'

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
