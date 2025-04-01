<script setup>

defineProps({
    theme: String
})

defineEmits(['changeTheme', 'changeNav'])

</script>

<template>
    <v-app-bar class="px-3" title="App">
        <template v-slot:append>
            <v-btn
                :icon="theme === 'dark' ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent'"
                @click="$emit('changeTheme')"
                density="comfortable"
                class="mr-3"
            />
            <v-btn class="text-none me-2" height="48" icon slim>
                <v-avatar icon="mdi-account" color="primary" size="32"/>

                <v-menu activator="parent">
                    <v-list density="compact" nav>
                        <v-list-item append-icon="mdi-cog-outline" link title="Settings"/>

                        <v-list-item append-icon="mdi-logout" link title="Logout"/>
                    </v-list>
                </v-menu>
            </v-btn>
        </template>

        <template v-slot:prepend>
            <v-app-bar-nav-icon @click="$emit('changeNav')"/>
        </template>
    </v-app-bar>
</template>
