<script setup>
    import { defineProps } from 'vue';
    import { VueSpinner } from 'vue3-spinners';

    defineProps({
        loading: {
            Boolean,
            default: false,
        },
    });
</script>
<style scoped>

</style>
<template>
    <div v-if="loading" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <VueSpinner size="30" color="black"/>
    </div>
</template>
