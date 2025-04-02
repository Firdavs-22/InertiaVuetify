<script setup>
import {router} from "@inertiajs/vue3";

defineProps({
    theme: String,
    user: Object,
})

defineEmits(['changeTheme', 'changeNav'])

const logout = () => {
    router.post("logout");
}

</script>

<template>
    <v-app-bar class="px-3" title="App">
        <template v-slot:append>
            <span class="ml-1 text-body-1 font-weight-medium">
                {{ user.name }}
            </span>
            <v-btn class="ml-1" height="48" slim icon>
                <v-avatar icon="mdi-account" color="primary" size="32" text="123"/>
                <v-menu activator="parent" open-on-hover close-delay="20000">
                    <v-list density="compact" nav>
                        <v-list-item
                            :append-icon="theme === 'dark' ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent'"
                            @click="$emit('changeTheme')"
                            title="Change Theme"
                        />

                        <v-list-item append-icon="mdi-cog-outline" link title="Settings"/>

                        <v-list-item append-icon="mdi-logout" @click.prevent="logout" link title="Logout"/>
                    </v-list>
                </v-menu>
            </v-btn>
        </template>

        <template v-slot:prepend>
            <v-app-bar-nav-icon @click="$emit('changeNav')"/>
        </template>
    </v-app-bar>
</template>
