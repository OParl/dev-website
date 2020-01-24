<template>
  <section>
    <vl-map
      :load-tiles-while-animating="true"
      :load-tiles-while-interacting="true"
      data-projection="EPSG:4326"
      style="height: 100vh">
      <vl-view :zoom.sync="zoom" :center.sync="center" :rotation.sync="rotation"></vl-view>
      <vl-layer-tile id="osm">
        <vl-source-osm></vl-source-osm>
      </vl-layer-tile>

      <vl-feature v-for="location in locations" :key="location.id" :id="location.id">
        <vl-geom-point :coordinates="location.geojson.geometry.coordinates"></vl-geom-point>
      </vl-feature>
    </vl-map>
  </section>
</template>

<script>
  import axios from 'axios'
  import Vue from 'vue'
  import { Map, TileLayer, OsmSource, Feature, PointGeom } from 'vuelayers'

  Vue.use(Map)
  Vue.use(TileLayer)
  Vue.use(OsmSource)
  Vue.use(Feature)
  Vue.use(PointGeom)

  export default {
    name: 'Overview',

    data() {
      return {
        zoom: 6,
        center: [9.270, 50.228],
        rotation: 0,
        locations: []
      }
    },

    mounted () {
      axios.get('/api/endpoints/1?include=bodies')
        .then(response => response.data.data.bodies)
        .then(bodies => bodies.filter(body => undefined !== body.json.location && undefined !== body.json.location.geojson))
        .then(bodies => bodies.map(body => body.json.location))
        .then(locations => this.locations = locations)
    }
  }
</script>
