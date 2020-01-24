<template>
  <section class="content">
    <h1>{{ endpoint.title }}</h1>

    <template v-if="endpoint.description">
      {{ endpoint.description }}
    </template>

    <div class="has-text-grey is-size-7">
      Zuletzt abgefragt {{ formatFetchDate(endpoint.fetched) }}
    </div>

    <h2>Verfügbare Körperschaften</h2>

    <table>
      <tbody>
      <tr v-for="body in endpoint.bodies">
        <td>
          {{ body.name }}<br>
          <template v-if="body.website">
            <a :href="body.website">{{ body.website }}</a><br>
            <br>
          </template>
          <pre>{{ body.oparlURL }}</pre>
        </td>
      </tr>
      </tbody>
    </table>
  </section>
</template>

<script>
  import axios from 'axios'
  import { formatRelative, parseISO } from 'date-fns'
  import de from 'date-fns/locale/de'

  export default {
    name: 'Bodies',

    props: {
      selectedEndpoint: {
        type: Number,
        required: true
      }
    },

    data () {
      return {
        endpoint: {}
      }
    },

    methods: {
      formatFetchDate (isoDate) {
        if (undefined === isoDate) {
          return ''
        }

        return formatRelative(parseISO(isoDate), new Date(), { locale: de })
      },

      loadEndpointWithBodies () {
        axios.get('/api/endpoints/' + this.selectedEndpoint + '?include=bodies')
          .then(response => response.data)
          .then(data => {
            this.$set(this, 'endpoint', data.data)
          })
      }
    },

    mounted () {
      this.loadEndpointWithBodies()
    },

    watch: {
      selectedEndpoint () { this.loadEndpointWithBodies() }
    }
  }
</script>

<style scoped>

</style>
