<script setup>
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

import AppNavigation from "@/layouts/app/AppNavigation.vue";
import AppBar from "@/layouts/app/AppBar.vue";


const drawer = ref(true)
const theme = ref('dark')


const links = [
    {title: 'Home', icon: 'mdi-home', url: '/'},
    {title: 'About', icon: 'mdi-account', url: '/about'},
    {title: 'Posts', icon: 'mdi-post', url: '/posts'},
]

const page = usePage()

</script>

<template>
    <v-app :theme="theme">
        <v-layout>
            <AppNavigation v-if="page.props.auth.user" :drawer="drawer" :links="links" :page="page" />

            <AppBar
                v-if="page.props.auth.user"
                :user="page.props.auth.user"
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
