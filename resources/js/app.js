let Prism = require('!!prismjs?lang=javascript');

import '@fortawesome/fontawesome-free/js/all';

import Vue from 'vue';

import Buefy from 'buefy';

import VueAffix from 'vue-affix'
import VueClipboard2 from 'vue-clipboard2';

Vue.use(Buefy, {
    defaultContainerElement: '#app',
    defaultIconPack: 'fas'
});

Vue.use(VueClipboard2);
Vue.use(VueAffix);

const vm = new Vue({
    el: '#app',

    methods: {
        triggerDownload(download) {
            document.location = '/downloads/spezifikation-' + download.version + '.' + download.format;
        },

        changeLiveView(version) {
            console.log({ version })
        }
    },

    mounted() {
        Prism.highlightAll();
    },
});
