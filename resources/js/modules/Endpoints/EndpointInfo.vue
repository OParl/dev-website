<template>
    <section>
        <strong>{{ endpoint.title }}</strong>
        <div v-if="endpoint.description" v-html="endpoint.description"></div>
        <pre class="small"><code>{{ endpoint.url }}</code></pre>
        <br>
        <b-collapse :open="false">
            <div class="card-header" slot="trigger" slot-scope="props">
                <strong class="card-header-title">Körperschaften</strong>
                <div class="card-header-icon">
                    <b-icon :icon="props.open ? 'expand' : 'compress'"></b-icon>
                </div>
            </div>
            <div class="card-content">
                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Url</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="body in endpoint.bodies">
                        <td>{{ body.name }}</td>
                        <td>{{ body.oparlURL }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </b-collapse>
        <br>
    </section>
</template>

<script>
    export default {
        name: 'endpoint-info',
        props: {
            endpoint: {
                required: true,
            }
        }
    }
</script>
