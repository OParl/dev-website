<script>
    export default {
        props: ['label', 'route', 'elements', 'icon'],
        computed: {
            listItems() {
                return JSON.parse(this.elements);
            }
        }
    }
</script>

<template>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <span v-if="label">{{ label }}</span>
            <span v-if="icon" class="glyphicon glyphicon-book"></span>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <slot></slot>
        </ul>
    </div>
</template>