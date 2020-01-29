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

<!--      <vl-feature v-for="area in areas" :key="area.id" :id="area.id">-->
<!--        <vl-geom-polygon :coordinates="areaCoordinates(area)"></vl-geom-polygon>-->
<!--      </vl-feature>-->
    </vl-map>
  </section>
</template>

<script>
  import axios from 'axios'
  import simplify from 'simplify-geojson'
  import Vue from 'vue'
  import { Map, TileLayer, OsmSource, Feature, PolygonGeom } from 'vuelayers'

  Vue.use(Map)
  Vue.use(TileLayer)
  Vue.use(OsmSource)
  Vue.use(Feature)
  Vue.use(PolygonGeom)

  export default {
    name: 'Overview',

    data() {
      return {
        zoom: 6,
        center: [9.270, 50.228],
        rotation: 0,
        areas: []
      }
    },

    methods: {
      areaCoordinates (area) {
        console.log(simplify(area.geojson, 40))

        return []
        // return area.geojson.geometry.coordinates;
      }
    },

    mounted () {
      axios.get('/api/endpoints/areas')
        .then(response => response.data.data)
        .then(areas => this.areas = areas)
    }
  }
</script>
