<template>
  <div>
    <h2>Endpunkt Statistik</h2>

    <ul v-if="statistics">
      <li>{{ statistics.systemCount }} bekannte Systeme</li>
      <li>
        {{ statistics.bodyCount }} Körperschaften insgesamt
        <span class="is-size-7">(<a href="#bodyCountNotice">Hinweis</a>)</span>
      </li>
      <li>
        Hersteller:

        <ul>
          <li v-for="vendor in statistics.vendors">
            <a :href="vendor">{{ vendor }}</a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    name: 'Statistics',

    data() {
      return {
        statistics: {}
      }
    },

    methods: {
      loadStatistics () {
        axios.get('/api/endpoints/statistics')
          .then(response => response.data)
          .then(data => {
            this.statistics = data.data
          })
      }
    },

    mounted () {
      this.loadStatistics()
    }
  }
</script>

<style scoped>

</style>
