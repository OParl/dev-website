import Vue from 'vue';
import VueClipboard2 from 'vue-clipboard2';
import Buefy from 'buefy';

Vue.use(Buefy, {
    defaultContainerElement: '#app',
    defaultIconPack: 'fas'
});
Vue.use(VueClipboard2);

const vm = new Vue({
    el: '#app',
});

window.vm = vm;
