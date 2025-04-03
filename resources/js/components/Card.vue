<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    title: String,
    content: String,
    username:String,
    avatar: String,
    image: String,
    tags: Array,
    publish_at: String,
});
</script>

<template>
    <v-card class="text-center pb-5">
        <v-img
            :src="image"
            height="200"
            class="bg-surface-lights"
            cover
        />
        <v-avatar
            :image="avatar"
            size="85"
            border="lg"
            class="mt-n13 ma-auto"
        />
        <v-card-item>
            <v-card-subtitle class="py-0">@{{ username }}</v-card-subtitle>
            <v-card-title class="text-h6">{{title}}</v-card-title>
            <v-card-text class=" text-medium-emphasis py-0 my-1">{{content}}</v-card-text>
            <v-card-subtitle class="py-0 text-subtitle-2 text-disabled">Published: {{publish_at}}</v-card-subtitle>
            <div>
                <v-chip-group
                    selected-class="text-primary"
                    column
                    class="text-medium-emphasis"
                >
                    <v-chip
                        v-for="tag in tags"
                        :key="tag"
                        density="compact"
                        size="small"
                        :text="'#'+tag"
                    />
                </v-chip-group>
            </div>
        </v-card-item>
    </v-card>
</template>
