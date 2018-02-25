import Vue from 'vue';

import Buefy from 'buefy';
import VueClipboard2 from 'vue-clipboard2';

Vue.use(Buefy, {
    defaultContainerElement: '#app',
    defaultIconPack: 'fas'
});
Vue.use(VueClipboard2);

export default Vue;