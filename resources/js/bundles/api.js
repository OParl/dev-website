import Buefy from 'buefy'
import OpenApi from '../modules/OpenApi.vue'
import Vue from 'vue'

Vue.use(Buefy, {
    defaultContainerElement: '#app'
})

new Vue({
    el: '#app',

    components: {
        OpenApi
    },
});
