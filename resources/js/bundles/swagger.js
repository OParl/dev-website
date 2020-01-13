import '@fortawesome/fontawesome-free/js/all'

import Vue from 'vue'

import Buefy from 'buefy'

Vue.use(Buefy, {
  defaultContainerElement: '#app',
  defaultIconPack: 'fab'
});

import Swagger from '../modules/Swagger.vue'

new Vue({
  el: '#app',

  components: {
    Swagger
  },
});
