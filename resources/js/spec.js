import Vue from 'vue'
import Helpers from './modules/helpers.js'

var vm = new Vue({
    el: 'body',

    data: {
    },

    methods: {
    },

    created() {
        Helpers.prismURLHelper();
    }
});

window.vm = vm;
