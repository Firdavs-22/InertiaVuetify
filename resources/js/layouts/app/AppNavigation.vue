<script setup>
import Link from "@/layouts/app/Link"


defineProps({
    drawer: Boolean,
    links: Array,
    page: Object
})


</script>

<template>
    <v-navigation-drawer :model-value="drawer" floating elevation="10">
        <div class="d-flex align-center pa-4">
            <v-avatar image="https://vuetifyjs.b-cdn.net/docs/images/logos/v.svg"/>
            <span class="text-h6 font-weight-medium">Campaign</span>
        </div>

        <div class="px-4 py-2">
            <v-text-field
                prepend-inner-icon="mdi-magnify"
                density="compact"
                label="Search"
                variant="outlined"
                bg-color="surface-light"
                hide-details
            />
        </div>

        <div class="px-2">
            <v-list density="comfortable" nav slim>
                <v-list-subheader title="Text" class="font-weight-bold"/>
                <Link
                    v-for="link in links"
                    :key="link.url"
                    :active="page.url === link.url"
                    :title="link.title"
                    :icon="link.icon"
                    :url="link.url"
                />
            </v-list>
        </div>
    </v-navigation-drawer>
</template>

<style scoped>

</style>
