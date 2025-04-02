<template>
    <v-container fluid>
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
    </v-container>
</template>

<script setup>
import { nextTick, ref, watch } from 'vue'

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
