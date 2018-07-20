<template>
    <div></div>
</template>

<script>
    import SwaggerUI from 'swagger-ui'

    export default {
        name: "Swagger",
        mounted() {
            SwaggerUI({
                domId: this.$el,
                url: '/api/swagger.json'
            });
        }
    }
</script>
