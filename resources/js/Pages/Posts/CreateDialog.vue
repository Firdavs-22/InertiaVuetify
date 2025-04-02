<script setup>
import { nextTick, ref, watch } from 'vue'

const props = defineProps({
    dialog: Boolean,
});

defineEmits(["closeDialog"]);


const items = ['Gaming', 'Programming', 'Vue', 'Vuetify']
const model = ref(['Vuetify'])
const search = ref(null)

watch(model, newTags => {
    if (newTags.length > 5) {
        nextTick(() => model.value.pop())
        return
    }

    if (newTags.length > 0) {
        const lastTag = newTags[newTags.length - 1]

        const isValid = lastTag.length > 2 && lastTag.length <= 15 && !/\s/.test(lastTag)

        if (!isValid) {
            nextTick(() => model.value.pop())
        }
    }
})
</script>

<template>
    <v-dialog
        max-width="800"
        :model-value="dialog"
        @update:model-value="$emit('closeDialog')"
    >
        <v-card
            title="Publish a Post"
            subtitle="Create your Post"
        >
            <template v-slot:text>
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            label="Title"
                            placeholder="Enter the post title"
                            variant="outlined"
                            hide-details/>
                    </v-col>

                    <v-col cols="12">
                        <v-textarea
                            label="Description"
                            placeholder="Enter the post description"
                            variant="outlined"
                            rows="3"
                            hide-details/>
                    </v-col>

                    <v-col cols="12">
                        <v-combobox
                            v-model="model"
                            v-model:search="search"
                            :hide-no-data="false"
                            :items="items"
                            hint="Maximum of 5 tags"
                            label="Add some tags"
                            chips
                            variant="outlined"
                            closable-chips
                            hide-selected
                            multiple
                            persistent-hint
                            clearable
                        >
                            <template v-slot:no-data>
                                <v-list-item>
                                    <v-list-item-title>
                                        No results matching "<strong>{{ search }}</strong>". Press <kbd>enter</kbd> to create a new one
                                    </v-list-item-title>
                                </v-list-item>
                            </template>
                        </v-combobox>
                    </v-col>

                </v-row>
            </template>

            <v-divider/>

            <v-card-actions class="px-6 py-4">
                <v-btn text="Cancel" variant="tonal" @click="$emit('closeDialog')"/>

                <v-spacer/>

                <v-btn text="Create" variant="tonal" />
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
