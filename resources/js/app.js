//let Prism = require('!!prismjs?lang=javascript');

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

import Endpoints_EndpointInfo from './modules/Endpoints/EndpointInfo.vue'
import Endpoints_EndpointStatistics from './modules/Endpoints/EndpointStatistics.vue'
import Endpoints_EndpointListFilter from './modules/Endpoints/EndpointListFilter.vue'

import LiveView_LiveView from './modules/LiveView/LiveView.vue'
import LiveView_TableOfContents from './modules/LiveView/TableOfContents.vue'
import LiveView_VersionSelector from './modules/LiveView/VersionSelector.vue'

new Vue({
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
            endpointsBodyFilter: ''
        }
    },

    computed: {
        bodies() {
            return this.endpoints.map(endpoint => endpoint.bodies).reduce((root, endpointBodyList) => root.concat(endpointBodyList), [])
        },

        filteredEndpoints() {
            return this.endpoints.filter(endpoint => {
                if (null === this.endpointsBodyFilter) return true;

                return endpoint.bodies.filter(body => body.name.match(this.endpointsBodyFilter)).length > 0;
            });
        }
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

                    //Prism.highlightAll();

                    this.liveView.isLoading = false;
                });
            }
        },

        filterEndpointsByBody(filter) {
            this.$set(this, 'endpointsBodyFilter', new RegExp(filter + '.*', 'i'));
        },

        getEndpoints() {
            axios.get('/api/endpoints').then(response => {
                return response.data.data;
            }, err => {
                // TODO: handle axios error
            }).then(data => {
                this.$set(this, 'endpoints', data);
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

    components: {
        'EndpointInfo': Endpoints_EndpointInfo,
        'EndpointListFilter': Endpoints_EndpointListFilter,
        'EndpointStatistics': Endpoints_EndpointStatistics,
        'LiveView': LiveView_LiveView,
        'TableOfContents': LiveView_TableOfContents,
        'VersionSelector': LiveView_VersionSelector,
    },
});
