<template>
    <v-text-field
        v-model="searchQuery"
        label="Search posts"
        prepend-inner-icon="mdi-magnify"
        class="mb-4"
        density="comfortable"
        clearable
        @update:modelValue="performSearch"
    />

    <v-data-table
        :headers="headers"
        :items="postList"
        item-value="id"
        show-expand
        hide-default-footer
        :loading="loading"
        class="rounded-lg"
    >
        <template v-slot:header.title="{column}">
            <th :align="column.align">
                <SortableHeader
                    label="Title"
                    sort-by="title"
                    :current-sort="sort"
                    @sort="handleSort"
                />
            </th>
        </template>

        <template v-slot:header.published_at="{ column }">
            <th :align="column.align">
                <SortableHeader
                    label="Published At"
                    sort-by="published_at"
                    :current-sort="sort"
                    @sort="handleSort"
                />
            </th>
        </template>

        <template v-slot:item.title="{value}">
            <div class="text-wrap truncate-multiline" style="max-width: 300px;">
                {{ value }}
                <v-tooltip location="top" activator="parent" max-width="1000" eager :text="value"/>
            </div>
        </template>

        <template v-slot:item.content="{value}">
            <div class="text-wrap truncate-multiline" style="max-width: 300px;">
                {{ value }}
            </div>
        </template>

        <template v-slot:item.user="{value}">
            <div class="d-flex flex-column align-center mt-1">
                <v-avatar :color="getAvatar(value.id).color" :icon="getAvatar(value.id).icon" size="32"/>
                <span>
                  {{ value.name }}
                </span>
            </div>
        </template>

        <template v-slot:item.tags="{value}">
            <v-chip-group column>
                <v-chip
                    v-for="tag in value.slice(0,3)"
                    :key="tag.id"
                    class="ma-1"
                    color="primary"
                    text-color="white"
                    size="small"
                    :text="'#'+tag.name"
                />
                <v-chip variant="text" text="..." v-if="value.length > 3" disabled/>
            </v-chip-group>
        </template>

        <template v-slot:item.data-table-expand="{ internalItem, isExpanded, toggleExpand }">
            <v-btn
                :append-icon="isExpanded(internalItem) ? 'mdi-chevron-up' : 'mdi-chevron-down'"
                :text="isExpanded(internalItem) ? 'Collapse' : 'More info'"
                class="text-none"
                color="medium-emphasis"
                size="small"
                variant="text"
                border
                slim
                @click="toggleExpand(internalItem)"
            />
        </template>

        <template v-slot:expanded-row="{ columns, item }">
            <tr>
                <td :colspan="columns.length" class="py-2">
                    <v-sheet rounded="lg" border>
                        <v-table density="compact">
                            <thead class="bg-surface-light">
                            <tr>
                                <th>Image</th>
                                <th>Content</th>
                                <th>Tags</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>
                                    <v-img
                                        width="300"
                                        :src="'/images/'+item.image"
                                        class="ma-1"
                                    />
                                </td>
                                <td class="py-2">{{ item.content }}</td>
                                <td>
                                    <v-chip-group column>
                                        <v-chip
                                            v-for="tag in item.tags"
                                            :key="tag.id"
                                            class="ma-1"
                                            color="primary"
                                            text-color="white"
                                            size="small"
                                            :text="'#'+tag.name"
                                        />
                                    </v-chip-group>
                                </td>
                            </tr>
                            </tbody>
                        </v-table>
                    </v-sheet>
                </td>
            </tr>
        </template>

        <template v-slot:loading>
            <v-skeleton-loader type="table-row@11"/>
        </template>
    </v-data-table>


    <v-pagination
        v-model="currentPage"
        total-visible="8"
        :length="lastPage"
        class="mt-4"
        :disabled="loading"
    >
        <template v-slot:prev="props">
            <Link
                as="button"
                :disabled="props.disabled || loading"
                :url="getPaginationData(currentPage - 1)"
                preserve-state
                preserve-scroll
                @start="loading = true"
                @finish="loading = false"
            >
                <v-btn
                    :disabled="props.disabled || loading"
                    icon="mdi-chevron-left"
                    variant="text"
                />
            </Link>
        </template>

        <template v-slot:item="{page, isActive}">
            <Link
                :disabled="isActive"
                as="button"
                :url="getPaginationData(page)"
                :active="isActive"
                preserve-state
                preserve-scroll
                @start="loading = true"
                @finish="loading = false"
                @cancel="loading = false"
            >
                <v-btn
                    :disabled="isActive || loading"
                    :active="isActive"
                    :variant="isActive ? 'flat' : 'text'"
                    :color="isActive ? 'primary' : undefined"
                    :text="page"
                />
            </Link>
        </template>

        <template v-slot:next="props">
            <Link
                as="button"
                :disabled="props.disabled || loading"
                :url="getPaginationData(currentPage + 1)"
                preserve-state
                preserve-scroll
                @start="loading = true"
                @finish="loading = false"
            >
                <v-btn
                    :disabled="props.disabled || loading"
                    icon="mdi-chevron-right"
                    variant="text"
                />
            </Link>
        </template>
    </v-pagination>
</template>

<script setup>
import {Link, router } from "@inertiajs/vue3";
import {getAvatar} from "@/utlis/avatar.js";
import {ref, watch} from "vue";
import SortableHeader from "@/Pages/Posts/SortableHeader.vue";

const headers = [
    { title: 'Title', key: 'title', align: 'start', sortable: false },
    { title: 'Content', key: 'content',sortable: false },
    { title: 'Username', key: 'user', sortable: false },
    { title: 'Published At', key: 'published_at', align: 'end', sortable: false },
    { title: 'Tags', key: 'tags', align: 'end' , sortable: false},
];



const props = defineProps({
    posts: Object,
    pagination: Object,
    sort: Object,
    search: String,
    currentPath: String
})

const currentPage = ref(props.pagination.currentPage)
const lastPage = ref(props.pagination.lastPage)
const loading = ref(false);
const postList = ref(props.posts.data)
const currentPath = ref(props.currentPath || window.location.pathname);

const searchQuery = ref(props.search);
const debounceTimeout = ref(null);
const handleSort = (newSort) => {
    const params = new URLSearchParams({
        ...Object.fromEntries(new URLSearchParams(window.location.search)),
        sort_by: newSort.key,
        sort_direction: newSort.direction,
        page: 1,
    });

    router.get(`${currentPath.value}?${params.toString()}`);
};

const performSearch = () => {
    clearTimeout(debounceTimeout.value);
    debounceTimeout.value = setTimeout(() => {
        const params = new URLSearchParams({
            ...Object.fromEntries(new URLSearchParams(window.location.search)),
            search: searchQuery.value,
            page: 1,
        });

        router.get(`${currentPath.value}?${params.toString()}`);
    }, 300);
};

const getPaginationData = (page) => {
    const params = new URLSearchParams({
        page: page,
        // Preserve existing filters from props
        ...(props.sort.by && { sort_by: props.sort.by }),
        ...(props.sort.direction && { sort_direction: props.sort.direction }),
        ...(props.search && { search: props.search }),
    });

    // Convert to string and remove empty params
    const paramString = params.toString().replace(/(^|&)[^&=]*=(?=&|$)/g, '');

    return `${currentPath.value}${paramString ? `?${paramString}` : ''}`;
}

watch(() => props.pagination.currentPage, (newVal) => {
    currentPage.value = newVal;
});
watch(() => props.posts.data, (newVal) => {
    postList.value = [...newVal];
});
watch(() => props.pagination.lastPage, (newVal) => {
    lastPage.value = newVal;
});
</script>

<style scoped>
.truncate-multiline {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
