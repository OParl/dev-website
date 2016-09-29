import Vue from 'vue'
import Helpers from './modules/helpers.js'

var vm = new Vue({
    el: 'body',

    created() {
        Helpers.prismURLHelper();
    },
});

window.vm = vm;
