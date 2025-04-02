import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import Vue from '@vitejs/plugin-vue';
import Vuetify from "vite-plugin-vuetify"
import {fileURLToPath, URL} from 'node:url'
import {tr} from "vuetify/locale";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        Vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false
                }
            }
        }),
        Vuetify()
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url))
        },
        extensions: ['.js', '.vue']
    },
    server: {
        host: "0.0.0.0",
        cors: true
    }
});
