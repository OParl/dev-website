import Vue from 'vue';
import Select from 'modules/select.vue'

var vm = new Vue({
    el: '#spec',
    components: {
        Select: Select
    }
});

window.vm = vm;
