<script setup>
import Card from "@/components/Card.vue";
import CreateDialog from "@/Pages/Posts/CreateDialog.vue";
import {ref, watchEffect} from "vue";
import {Deferred, WhenVisible} from "@inertiajs/vue3";

const props = defineProps({
    posts: Object,
    tags: Array,
})

const isDataLoaded = ref(false);
const dialog = ref(false);
const posts = ref(props.posts.data);


watchEffect(() => {
    if (props.tags) {
        isDataLoaded.value = true
        dialog.value = false
    }
})

const handleOpenDialog = () => dialog.value = true

const handleCloseDialog = () => dialog.value = false

const addPost = (newPost) => {
    posts.value = [newPost, ...posts.value]
}


</script>

<template>
    <div class="d-flex justify-space-between align-center mb-4">
        <span class="text-h4">The Post List</span>
        <v-btn
            color="primary"
            text="create post"
            @click="handleOpenDialog"
            :loading="!isDataLoaded"
        />
    </div>
    <v-row ref="scrollContainer" style="max-height: 45em; overflow-y: auto;">
        <v-col
            v-for="post in posts"
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
                :image="'/images/'+post.image"
                :tags="post.tags"
                :published_at="post.published_at"
            />
        </v-col>

        <WhenVisible
            v-if="props.posts.meta.current_page < props.posts.meta.last_page"
            always
            :params="{
                data: {page: props.posts.meta.current_page + 1},
                only: ['posts'],
                preserveState: true,
                preserveScroll: true,
                preserveUrl: true,
                onSuccess: (data) => {
                    const newItems = data.props.posts.data
                        .filter(newItem => !posts.some(post => post.id === newItem.id))
                    if (newItems.length) posts.push(...newItems)

                }
            }"
        >
        </WhenVisible>
    </v-row>
    <Deferred data="tags">
        <template #fallback/>
        <CreateDialog :dialog="dialog" @closeDialog="handleCloseDialog" :tags="tags" @addPost="addPost"/>
    </Deferred>
</template>
