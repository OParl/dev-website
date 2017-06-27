<template>
    <f-accordion>
        <span slot="title">{{ title }}</span>
        <div slot="body"><slot></slot></div>
    </f-accordion>
</template>

<script>
    import FAccordion from './faccordion.vue'

    export default {
        name: 'SpecificationExample',

        props: {
            title: {
                required: true,
                type: String
            }
        },

        components: {
            FAccordion
        }
    }
</script>