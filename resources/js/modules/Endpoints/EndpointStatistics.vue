<template>
    <div id="statistik">
        <h2>Endpunkt Statistik</h2>

        <ul>
            <li>Körperschaften gesamt: {{ bodyCount }}</li>
            <li>Letzte Aktualisierung: {{ latestFetch }}</li>
            <li>
                Hersteller:

                <ul>
                    <li v-for="vendor in vendors">
                        <a :href="vendor">{{ vendor }}</a>
                    </li>
                </ul>
            </li>
        </ul>

        <p>
            <em>
                Die Anzahl der Körperschaften entspricht nicht zwingend der Anzahl an Kommunen
                in Deutschland die bereits OParl unterstützen. Dies hängt zum Einen damit
                zusammen, dass OParl-Daten von Projekten bereitgestellt werden, welche gar nicht
                auf kommunaler Datenbasis aufsetzen (z.B.: kleineAnfragen), zum Anderen können
                auf Grund der Natur von OParl-Systemen Körperschaften in mehr als
                einem dieser vorkommen.
            </em>
        </p>
    </div>
</template>

<script>
    export default {
        name: "EndpointStatistics",
        props: {
            endpoints: {
                required: true,
            }
        },
        computed: {
            bodyCount() {
                return this.endpoints.reduce((carry, system) => carry + system.bodies.length, 0);
            },
            latestFetch() {
                return this.endpoints.map(endpoint => endpoint.fetched).sort().pop()
            },
            vendors() {
                return this.endpoints.map(endpoint => endpoint.system.vendor).filter(
                    (value, index, self) => self.indexOf(value) === index
                ).filter(value => typeof value === 'string')
            }
        }
    }
</script>
