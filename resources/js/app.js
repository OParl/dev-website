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

import EndpointInfo from './modules/EndpointInfo.vue'
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
                toc: '',
                versionOnLoad: '1.1'
            },
            endpoints: [],
        }
    },

    components: {
        'EndpointInfo': EndpointInfo,
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
                    // TODO: handle axios error
                }).then(data => {
                    this.liveView.currentVersion.human = data.currentVersion;
                    this.liveView.body = data.body;
                    this.liveView.toc = data.toc;
                    Prism.highlightAll();

                    this.liveView.isLoading = false;
                });
            }
        },

        getEndpoints() {
            axios.get('/api/endpoints').then(response => {
                return response.data.data;
            }, err => {
                // TODO: handle axios error
            }).then(data => {
                this.endpoints = data;
            });
        }
    },

    mounted() {
        if (document.location.href.indexOf('/spezifikation') > 0) {
            let version = this.liveView.versionOnLoad;

            if (document.location.search.indexOf('version=') > 0) {
                version = document.location.search.split('=')[1];
            }

            this.changeLiveView(version);
        }

        if (document.location.href.indexOf('/endpunkte') > 0) {
            this.getEndpoints();
        }

        Prism.highlightAll();
    },
});
