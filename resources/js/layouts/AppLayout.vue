<script setup>
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

import AppNavigation from "@/layouts/app/AppNavigation.vue";
import AppBar from "@/layouts/app/AppBar.vue";


const drawer = ref(true)
const theme = ref('dark')


const links = [
    {title: 'Profile', icon: 'mdi-home', url: '/'},
    {title: 'About', icon: 'mdi-account', url: '/about'},
    {title: 'Login', icon: 'mdi-login', url: '/login'},
    {title: 'Register', icon: 'mdi-account-plus', url: '/register'},
]

const page = usePage()

</script>

<template>
    <v-app :theme="theme">
        <v-layout>
            <AppNavigation  :drawer="drawer" :links="links" :page="page" />

            <AppBar
                :theme="theme"
                @changeTheme="theme = theme === 'dark' ? 'light' : 'dark'"
                @changeNav="drawer = !drawer"
            />

            <v-main>
                <v-container>
                    <slot/>
                </v-container>
            </v-main>
        </v-layout>
    </v-app>
</template>

