<template>
    <div></div>
</template>

<script>
    export default {
        name: "Swagger",
        mounted() {
            import('swagger-ui').then(SwaggerUI => {
                SwaggerUI.default({
                    domNode: this.$el,
                    url: '/api/swagger.json'
                })
            })
        }
    }
</script>
