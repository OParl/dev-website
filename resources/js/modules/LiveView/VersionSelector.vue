<template>
    <b-dropdown v-if="current">
        <button class="button" slot="trigger">
            {{ current }}&nbsp;<i class="fa fa-caret-down"></i>
        </button>
        <b-dropdown-item
                v-for="version in versions"
                :key="version"
                @click="fetch(version)">
            {{ version }}
        </b-dropdown-item>
    </b-dropdown>
</template>

<script>
    export default {
        name: "VersionSelector",

        props: {
            versions: {
                type: Array,
                required: true
            },
            current: {
                type: String,
                required: true
            }
        },

        methods: {
            fetch(version) {
                this.$emit('version-change', version);
            }
        }
    }
</script>
