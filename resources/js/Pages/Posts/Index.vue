<script setup>
import Card from "@/components/Card.vue";
import CreateDialog from "@/Pages/Posts/CreateDialog.vue";
import {nextTick, ref, watchEffect} from "vue";
import {Deferred, router} from "@inertiajs/vue3";

const props = defineProps({
    posts: Object,
    tags: Array,
})

const isDataLoaded = ref(false);
const dialog = ref(false);

watchEffect(() => {
    if (props.tags) {
        isDataLoaded.value = true
        dialog.value = false
    }
})

const handleOpenDialog = () => dialog.value = true

const handleCloseDialog = () => dialog.value = false

const reloadPosts = async () => {
    console.log("reload posts")
    handleCloseDialog()
    await nextTick()
    router.reload({
        only: ['posts'],
    })
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
    <Deferred data="tags">
        <template #fallback/>
        <CreateDialog :dialog="dialog" @closeDialog="handleCloseDialog" :tags="tags" @reloadPosts="reloadPosts"/>
    </Deferred>
</template>
