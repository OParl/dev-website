<template>
    <div>
        <input type="text" placeholder="{{ label }}" />
        <div>
            <!-- list elements -->
            <ul>

            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['label']
    }
</script>
