<script setup>
import {Link} from "@inertiajs/vue3";
defineProps({
    active: Boolean,
    title: String,
    icon: String,
    url: String
})
</script>

<template>
    <Link :href="url" class="text-decoration-none text-high-emphasis">
        <v-list-item
            link
            :active="active"
            rounded="lg"
            :prepend-icon="icon"
            :title="title"
        />
    </Link>
</template>

