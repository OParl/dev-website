import Buefy from 'buefy'
import List from '../modules/Endpoints/List'
import Vue from 'vue'

Vue.use(Buefy, {
  defaultContainerElement: '#app'
})

new Vue({
  el: '#app',

  components: {
    List
  },
});
