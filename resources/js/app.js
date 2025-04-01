import {createApp, h} from "vue";
import {createInertiaApp} from "@inertiajs/vue3";
import {vuetify} from "@/vuetify";
import AppLayout from "@/layouts/AppLayout.vue";



createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true});
        let page = pages[`./Pages/${name}.vue`];
        page.default.layout = page.default.layout || AppLayout
        return page;
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(vuetify)
            .mount(el);
    },

})
