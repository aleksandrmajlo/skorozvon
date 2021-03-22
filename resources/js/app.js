require('./bootstrap');
require('./common');
window.Vue = require('vue');

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);


import Vue from 'vue/dist/vue.esm.browser';
import 'vue-loaders/dist/vue-loaders.css';
import VueLoaders from 'vue-loaders';
Vue.use(VueLoaders);



// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('authentication-log', require('./components/operator/AuthenticationLog.vue').default);
Vue.component('shipping-permission', require('./components/settings/ShippingPermission.vue').default);
Vue.component('setting-admin', require('./components/settings/SettingAdmin.vue').default);

// контакты
//лог
Vue.component('contact-log', require('./components/contacts/ContactLog.vue').default);
// опросить банки
Vue.component('contact-bank', require('./components/contacts/ContactBank.vue').default);
// DatepickerWrap
Vue.component('datepicker-wrap', require('./components/contacts/DatepickerWrap.vue').default);

const app = new Vue({
    el: '#app',
});
