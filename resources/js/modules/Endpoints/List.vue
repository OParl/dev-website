<template>
  <div class="columns">
    <div class="column is-two-fifths">
      <div class="level">
        <div class="level-left">
<!--          <div class="level-item">-->
<!--            <div class="control">-->
<!--              <button class="button" @click="toggleState('overview')">-->
<!--                Überblick-->
<!--              </button>-->
<!--            </div>-->
<!--          </div>-->
          <div class="level-item">
            <div class="control">
              <button class="button" @click="toggleState('statistics')">
                Statistik
              </button>
            </div>
          </div>
        </div>

        <div class="level-right">
          <div class="level-item">
            <sliding-pagination
              :current="page"
              :total="total"
              @page-change="loadPage"
            >
            </sliding-pagination>
          </div>
        </div>
      </div>

      <template
        v-for="endpoint in endpoints"
      >
        <div class="card" :key="endpoint.id">
          <div
            :class="endpoint.id === selectedEndpoint ? 'has-background-primary' : ''"
            class="card-header">
            <span
              :class="endpoint.id === selectedEndpoint ? 'has-text-white' : ''"
              class="card-header-title">
              {{ endpoint.title }}
            </span>
          </div>
          <div
            class="card-content">
            <template v-if="endpoint.description">
              <div class="content">{{ endpoint.description }}</div>
            </template>

            <div class="level">
              <div class="level-item">
                <button type="button" class="button" @click="toggleState('details', endpoint.id)">
                  {{ endpoint.bodyCount }} {{ bodiesBtnLabel(endpoint.bodyCount) }}
                </button>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="has-text-grey is-size-7 card-footer-item">
              Zuletzt abgefragt {{ formatFetchDate(endpoint.fetched) }}
            </div>
          </div>
        </div>
        <br>
      </template>

      <div class="level">
        <div class="level-left">
          <div class="level-item">&nbsp;</div>
        </div>
        <div class="level-right">
          <div class="level-item">
            <sliding-pagination
              :current="page"
              :total="total"
              @page-change="loadPageAndScrollTop"
            >
            </sliding-pagination>
          </div>
        </div>
      </div>
    </div>
    <div class="column is-three-fifths">
      <component
        :selectedEndpoint="selectedEndpoint"
        :is="detailComponent"
        keep-alive>
      </component>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import { formatRelative, parseISO } from 'date-fns'
  import de from 'date-fns/locale/de'
  import Bodies from './Bodies'
  import Overview from './Overview'
  import Statistics from './Statistics'
  import SlidingPagination from 'vue-sliding-pagination'

  export default {
    name: 'List',

    data () {
      return {
        endpoints: [],
        endpointsPerPage: 10,
        selectedEndpoint: null,
        page: 1,
        total: 1,
        state: 'statistics'
      }
    },

    computed: {
      detailComponent () {
        switch (this.state) {
          case 'overview':
            return Overview.name

          case 'statistics':
            return Statistics.name

          case 'details':
            return Bodies.name
        }
      }
    },

    methods: {
      bodiesBtnLabel (count) {
        return (1 < count || 0 === count) ? 'Körperschaften' : 'Körperschaft'
      },

      formatFetchDate (isoDate) {
        return formatRelative(parseISO(isoDate), new Date(), { locale: de })
      },

      loadPage (page) {
        return axios.get(`/api/endpoints?limit=${this.endpointsPerPage}&page=${page}`)
          .then(response => response.data)
          .then(data => {
            this.$set(this, 'endpoints', data.data)
            this.page  = data.meta.page
            this.total = data.meta.totalPages
          })
      },

      loadPageAndScrollTop (page) {
        return this.loadPage(page).then(() => {
          scrollTo({ top: 0, behavior: 'smooth' })
        })
      },

      toggleState (state, endpointId) {
        this.state = state
        this.selectedEndpoint = endpointId
      }
    },

    mounted () {
      this.loadPage(1)
    },

    components: {
      Bodies,
      Overview,
      SlidingPagination,
      Statistics
    }
  }
</script>
