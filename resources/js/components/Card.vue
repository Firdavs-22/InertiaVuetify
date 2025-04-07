<script setup>
import {defineProps} from 'vue';
import {getAvatar} from "@/utlis/avatar.js";

const props = defineProps({
    title: String,
    content: String,
    username: String,
    avatar: Number,
    image: String,
    tags: Array,
    published_at: String,
});

const {icon, color} = getAvatar(props.avatar)


</script>

<template>
    <v-card class="text-center pb-5">
        <v-img
            :src="image"
            height="200"
            class="bg-surface-lights"
            cover
        >
            <template v-slot:placeholder>
                <v-skeleton-loader
                    class="mx-auto"
                    max-width="500"
                    type="image"
                />
            </template>
        </v-img>
        <v-avatar
            :color="color"
            border="lg"
            class="mt-n7 ma-auto"
            size="50"
        >
            <v-icon :icon="icon" size="x-large"/>
        </v-avatar>
        <v-card-item>
            <v-card-subtitle class="py-0">@{{ username }}</v-card-subtitle>
            <v-card-title class="text-h6">{{ title }}</v-card-title>
            <v-card-text class=" text-medium-emphasis py-0 my-1">{{ content }}</v-card-text>
            <v-card-subtitle class="py-0 text-subtitle-2 text-disabled">Published: {{
                    published_at
                }}
            </v-card-subtitle>
            <div>
                <v-chip-group
                    selected-class="text-primary"
                    column
                    class="text-medium-emphasis"
                >
                    <v-chip
                        v-for="(tag, index) in tags"
                        :key="tag.id ?? index"
                        density="compact"
                        size="small"
                        :text="'#'+(tag.name ?? tag)"
                    />
                </v-chip-group>
            </div>
        </v-card-item>
    </v-card>
</template>
