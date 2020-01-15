<template>
    <section>
        <strong>{{ endpoint.title }}</strong>
        <div v-if="endpoint.formattedDescription" v-html="endpoint.formattedDescription"></div>

        <pre><code>{{ endpoint.url }}</code></pre>

        <b-collapse :open="false">
            <button class="button" slot="trigger">Körperschaften</button>
            <br>
            <div class="notification">
                <div class="content">
                    <table>
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Url</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="body in endpoint.bodies">
                            <td>
                                {{ body.name }}<br>
                                <a :href="body.website">{{ body.website }}</a>
                            </td>
                            <td>{{ body.oparlURL }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
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
            },
        },

        methods: {
            copySuccess(e) {
                this.$toast.open({
                    message: "Der Endpunkt wurde in die Zwischenablage kopiert",
                    //container: '.toast-container',
                    duration: 5000,
                    position: 'is-bottom-left'
                });
            },
        },
    }
</script>
