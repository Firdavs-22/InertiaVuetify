<script setup>
import Card from "@/components/Card.vue";
import CreateDialog from "@/Pages/Posts/CreateDialog.vue";
import {ref} from "vue";

defineProps({
    posts: Array
})


const dialog = ref(false);
</script>

<template>
    <div class="d-flex justify-space-between align-center mb-4">
        <span class="text-h4">The Post List</span>
        <v-btn
            color="primary"
            text="create post"
            @click="dialog = true"
        />
    </div>
    <v-row style="max-height: 45em; overflow-y: auto;">
        <v-col
            v-for="post in posts.data"
            :key="post.id"
            sm="6"
            md="4"
            cols="12"
        >
            <Card
                :title="post.title"
                :content="post.content"
                :username="post.user.name"
                :avatar="post.user.id"
                :image="'images/'+post.image"
                :tags="post.tags"
                :published_at="post.published_at"
            />
        </v-col>
    </v-row>
    <CreateDialog :dialog="dialog" @close-dialog="dialog = false"/>
</template>
