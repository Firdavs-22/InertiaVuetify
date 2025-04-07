<script setup>
import {useForm, Link, usePage} from "@inertiajs/vue3";
import {nextTick, ref, watch} from "vue";
import Card from "@/components/Card.vue";

defineProps({
    tags: {
        type: Array,
        required: true
    },
})

const form = useForm({
    title: '',
    content: '',
    tags: [],
    image: null,
    imagePreview: null,
})
const { props: {auth: {user}}} = usePage()

const search = ref(null)
const postActive = ref(false)
const previewDialog = ref(false)

watch(() => form.tags, newTags => {
    if (newTags.length > 5) {
        nextTick(() => form.tags.pop())
        return
    }

    if (newTags.length > 0) {
        const lastTag = newTags[newTags.length - 1]

        const isValid = lastTag.length > 2 && lastTag.length <= 15 && !/\s/.test(lastTag)

        if (!isValid) {
            nextTick(() => form.tags.pop())
        }
    }
})

watch(form, (newVal) => {
    postActive.value = !!(newVal.imagePreview && newVal.title && newVal.content && newVal.tags.length > 0);
})

const imageRule = [
    value => {
        if (value && value.size < (10 * 1_000_000)) {
            new Promise((resolve) => {
                const reader = new FileReader()
                reader.onload = () => {
                    form.imagePreview = reader.result
                    resolve(true)
                }
                reader.readAsDataURL(value)
            })
        }
        return !value || value.size < (10 * 1_000_000) || 'File size should be less than 10MB!'
    }
]

const handleCreate = () => {
    if (postActive.value) {
        form.transform((data) => {
            const {imagePreview, ...rest} = data
            return rest
        }).post("/posts", {
            onSuccess: () => {
                form.reset()
                previewDialog.value = false
            },
        })
    }
}
</script>

<template>
    <v-card
        title="Publish a Post"
        subtitle="Create your Post"
        :loading="form.processing"
    >
        <template v-slot:text>
            <v-row>
                <v-col cols="12">
                    <v-text-field
                        label="Title"
                        placeholder="Enter the post title"
                        variant="outlined"
                        hide-details
                        v-model="form.title"
                        :error-messages="form.errors.title"
                    />
                </v-col>

                <v-col cols="12">
                    <v-textarea
                        label="Description"
                        placeholder="Enter the post description"
                        variant="outlined"
                        rows="3"
                        hide-details
                        v-model="form.content"
                        :error-messages="form.errors.content"
                    />
                </v-col>

                <v-col cols="12" md="6">
                    <v-combobox
                        v-model="form.tags"
                        v-model:search="search"
                        :hide-no-data="false"
                        :items="tags"
                        hint="Maximum of 5 tags, Minimum of 1 tag"
                        label="Add some tags"
                        chips
                        :error-messages="form.errors.tags"
                        variant="outlined"
                        closable-chips
                        hide-selected
                        multiple
                        persistent-hint
                        clearable
                        :return-object="false"
                    >
                        <template v-slot:no-data>
                            <v-list-item>
                                <v-list-item-title>
                                    No results matching "<strong>{{ search }}</strong>". Press <kbd>enter</kbd> to
                                    create a new one
                                </v-list-item-title>
                            </v-list-item>
                        </template>
                    </v-combobox>
                </v-col>

                <v-col cols="12" md="6">
                    <v-file-input
                        label="Upload a Image"
                        variant="outlined"
                        hint="Only image files are allowed (max 10MB)"
                        persistent-hint
                        clearable
                        :rules="imageRule"
                        :error-messages="form.errors.image"
                        accept="image/*"
                        v-model="form.image"
                    />
                </v-col>

            </v-row>
        </template>

        <v-divider/>

        <v-card-actions class="px-6 py-4">
            <Link href="/posts" class="text-decoration-none">
                <v-btn variant="flat" color="blue-grey" text="Back"/>
            </Link>

            <v-spacer/>

            <v-btn text="preview" :disabled="!postActive" variant="flat" color="secondary" class="mr-2"
                   @click="previewDialog = true"/>


            <v-btn text="Create" variant="flat" :disabled="!postActive" color="primary" @click="handleCreate"
                   :loading="form.processing"/>
        </v-card-actions>
    </v-card>

    <v-dialog
        v-model="previewDialog"
        max-width="350"
    >
        <Card
            :title="form.title"
            :content="form.content"
            :username="user.name"
            :avatar="user.id"
            :image="form.imagePreview"
            :tags="form.tags"
            published_at="Not Published"
        />
    </v-dialog>
</template>
