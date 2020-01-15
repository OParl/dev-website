<template>
    <div></div>
</template>

<script>
    import SwaggerUI from 'swagger-ui'

    export default {
        name: "OpenApi",
        mounted() {
            SwaggerUI({
                domNode: this.$el,
                url: '/api/openapi.json'
            });
        }
    }
</script>
