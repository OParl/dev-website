import Vue from 'vue'
import VueResource from 'vue-resource'
import Helpers from './modules/helpers.js'

Vue.use(VueResource);

var vm = new Vue({
    el: 'body',

    ready() {
        Helpers.prismURLHelper();
    }
});