<template>
    <div class="panel">
        <div class="panel-body">
            <b-field label="Endpunkte nach Körperschaft filtern:">
                <b-autocomplete
                        :data="bodies"
                        @input="filter"
                        field="name"
                >
                    <template slot-scope="props">
                        {{ props.option.name }}
                        <span v-if="props.option.website">({{ props.option.website }})</span>
                    </template>
                </b-autocomplete>
            </b-field>
        </div>
    </div>
</template>

<script>
    export default {
        name: "EndpointListFilter",

        props: {
            bodies: {
                required: true,
                type: Array
            }
        },

        data() {
            return {
                body: null
            }
        },

        methods: {
            filter(filter) {
                this.$emit('endpoint-filter-list:filter', filter)
            }
        }
    }
</script>
