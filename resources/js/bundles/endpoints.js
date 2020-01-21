import axios from 'axios'
import Buefy from 'buefy'
import EndpointInfo from '../modules/Endpoints/EndpointInfo.vue'
import EndpointStatistics from '../modules/Endpoints/EndpointStatistics.vue'
import EndpointListFilter from '../modules/Endpoints/EndpointListFilter.vue'
import Vue from 'vue'

Vue.use(Buefy, {
  defaultContainerElement: '#app'
})

new Vue({
  el: '#app',

  data () {
    return {
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
      this.getEndpoints();
  },

  components: {
    EndpointInfo,
    EndpointListFilter,
    EndpointStatistics,
  },
});
