let Prism = require('!!prismjs?lang=javascript');

import Vue from 'vue'

import Buefy from 'buefy'
import VueAffix from 'vue-affix'
import VueClipboard2 from 'vue-clipboard2'

Vue.use(Buefy, {
  defaultContainerElement: '#app'
});

Vue.use(VueClipboard2);
Vue.use(VueAffix);

import axios from 'axios'

import LiveView from '../modules/LiveView/LiveView.vue'
import TableOfContents from '../modules/LiveView/TableOfContents.vue'
import VersionSelector from '../modules/LiveView/VersionSelector.vue'

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
      }
    }
  },

  methods: {
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
  },

  mounted() {
    if (document.location.href.indexOf('/spezifikation') > 0) {
      let version = this.liveView.versionOnLoad;

      if (document.location.search.indexOf('version=') > 0) {
        version = document.location.search.split('=')[1];
      }

      this.changeLiveView(version);
    }
  },

  components: {
    LiveView,
    TableOfContents,
    VersionSelector,
  },
});
