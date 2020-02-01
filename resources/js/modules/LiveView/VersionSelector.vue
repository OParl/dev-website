<template>
    <b-dropdown v-if="currentOfficial">
        <button class="button" slot="trigger">
            <span :title="'Git Hash: ' + currentHash">{{ currentOfficial }}</span>&nbsp;<i class="fa fa-caret-down"></i>
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
            currentOfficial: {
                type: String,
                required: true
            },
            currentHash: {
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
