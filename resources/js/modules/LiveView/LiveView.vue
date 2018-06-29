<template>
    <div v-html="html"></div>
</template>

<script>
    export default {
        name: "LiveView",
        props: {
            html: {
                type: String,
                required: true
            }
        }
    }
</script>
