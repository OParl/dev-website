<template>
    <b-collapse class="card" :open="false">
        <div class="card-header" slot="trigger" slot-scope="props">
            <strong class="card-header-title">Inhaltsverzeichnis</strong>
            <div class="card-header-icon">
                <b-icon :icon="props.open ? 'expand' : 'compress'"></b-icon>
            </div>
        </div>
        <div class="card-content table-of-contents" v-html="html"></div>
    </b-collapse>
</template>

<script>
    export default {
        name: "TableOfContents",
        props: {
            html: {
                type: String,
                required: true
            }
        }
    }
</script>
