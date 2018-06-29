let Prism = require('!!prismjs?lang=javascript');

import '@fortawesome/fontawesome-free/js/all'

import Vue from 'vue'

import Buefy from 'buefy'
import VueAffix from 'vue-affix'
import VueClipboard2 from 'vue-clipboard2'

Vue.use(Buefy, {
    defaultContainerElement: '#app',
    defaultIconPack: 'fas'
});

Vue.use(VueClipboard2);
Vue.use(VueAffix);

import axios from 'axios'

import LiveView_LiveView from './modules/LiveView/LiveView.vue'
import LiveView_TableOfContents from './modules/LiveView/TableOfContents.vue'
import LiveView_VersionSelector from './modules/LiveView/VersionSelector.vue'

const vm = new Vue({
    el: '#app',

    data() {
        return {
            liveView: {
                isLoading: false,
                currentVersion: {
                    human: '',
                    fetch: ''
                },
                body: '',
                toc: ''
            }
        }
    },

    components: {
        'LiveView': LiveView_LiveView,
        'TableOfContents': LiveView_TableOfContents,
        'VersionSelector': LiveView_VersionSelector,
    },

    methods: {
        triggerDownload(download) {
            document.location = '/downloads/spezifikation-' + download.version + '.' + download.format;
        },

        changeLiveView(version) {
            if (this.liveView.currentVersion.fetch !== version) {
                this.liveView.isLoading = true;
                axios.get('/api/specification/' + version).then(response => {
                    return response.data;
                }, err => {
                    // handle axios error
                }).then(data => {
                    this.liveView.currentVersion.human = data.currentVersion;
                    this.liveView.body = data.body;
                    this.liveView.toc = data.toc;

                    this.liveView.isLoading = false;
                });
            }
        }
    },

    mounted() {
        this.changeLiveView(1.1);
        Prism.highlightAll();
    },
});
