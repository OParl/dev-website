import Vue from 'vue'
import VueResource from 'vue-resource'

Vue.use(VueResource);

var vm = new Vue({
    el: '#specification-sub-nav'
});

window.vm = vm;
