<script setup>
import Card from "@/components/Card.vue";
import CreateDialog from "@/Pages/Posts/CreateDialog.vue";
import {ref} from "vue";

const card = {
    title: "Title",
    content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
    user: {
        name: "Test",
        image: "https://vuetifyjs.b-cdn.net/images/john-smirk.png",
    },
    image: "https://vuetifyjs.b-cdn.net/docs/images/one/snips/backgrounds/landscape06.png",
    tags: ["Hello", "Lorem", "Test", "one", "to", "gg"],
    publish_at: "1 hour ago",
}

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
    <v-row style="max-height: 45em; overflow-y: auto;" >
        <v-col
            v-for="index in 20"
            :key="index"
            sm="6"
            md="4"
            cols="12"
        >
            <Card
                :title="card.title"
                :content="card.content"
                :username="card.user.name"
                :avatar="card.user.image"
                :image="card.image"
                :tags="card.tags"
                :publish_at="card.publish_at"
            />
        </v-col>
    </v-row>
    <CreateDialog :dialog="dialog" @close-dialog="dialog = false"/>
</template>
