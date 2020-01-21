import Vue from 'vue'

import Buefy from 'buefy'
import VueAffix from 'vue-affix'

Vue.use(Buefy, {
  defaultContainerElement: '#app'
});

Vue.use(VueAffix);
import VersionSelector from '../modules/LiveView/VersionSelector'

new Vue({
  el: '#app',

  computed: {
    affixWidth () {
      return document.getElementById('toc-container').clientWidth - 20;
    },

    formattedAffixWidth () {
      return 'width: ' + this.affixWidth + 'px';
    }
  },

  methods: {
    changeLiveView(version) {
      window.location.pathname = '/spezifikation/' + version;
    }
  },

  components: {
    VersionSelector
  }
});
