import Vue from 'vue';
import VueClipboard2 from 'vue-clipboard2';

Vue.use(VueClipboard2);

const vm = new Vue({
    el: '#app',
});

window.vm = vm;
