<template>
    <div class="d-flex align-center cursor-pointer" @click="toggleSort">
        <span>{{ label }}</span>
        <v-icon v-if="showSort" :class="{'flip-icon': direction === 'desc'}" size="small">
            mdi-arrow-up
        </v-icon>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    label: String,
    sortBy: String,
    currentSort: Object,
});

const emit = defineEmits(['sort']);

const showSort = computed(() => props.currentSort.by === props.sortBy);
const direction = computed(() => showSort.value ? props.currentSort.direction : 'asc');

const toggleSort = () => {
    const newDirection = showSort.value && direction.value === 'asc' ? 'desc' : 'asc';
    emit('sort', { key: props.sortBy, direction: newDirection });
};
</script>

<style>
.cursor-pointer { cursor: pointer; }
.flip-icon { transform: scaleY(-1); }
</style>
