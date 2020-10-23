require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';

Vue.mixin({ methods: { route } });
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);

const app = document.getElementById('app');
const sweetAlert = require('sweetalert2').default;
window.Toast = sweetAlert.mixin({
    toast: true,
    position: 'top-right',
    timer: 3000,
    timerProgressBar: true,
})

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
