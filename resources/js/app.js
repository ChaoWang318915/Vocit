/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// import Vue from 'vue'
// import VueRx from 'vue-rx'
// import VuejsClipper from 'vuejs-clipper'
// // install vue-rx
// Vue.use(VueRx)
// // install vuejs-clipper
// Vue.use(VuejsClipper)
import VModal from 'vue-js-modal';
Vue.use(VModal);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('posts-component', require('./components/PostsComponent').default);
Vue.component('business-component', require('./components/BusinessComponent').default);
Vue.component('request-component', require('./components/RequestComponent').default);
Vue.component('parent-component', require('./components/ParentComponent').default);
Vue.component('exchange-component', require('./components/ExchangeComponent').default);
Vue.component('business-profile-component', require('./components/BusinessProfileComponent').default);
Vue.component('profile-component', require('./components/ProfileComponent').default);
Vue.component('business-public-component', require('./components/BusinessPublicComponent').default);
Vue.component('business-wallet-component', require('./components/BusinessWalletComponent').default);
Vue.component('user-wallet-component', require('./components/WalletComponent').default);
Vue.component('settings-component', require('./components/SettingsComponent').default);
Vue.component('payment-component', require('./components/PaymentComponent').default);
Vue.component('subscription-component', require('./components/SubscriptionComponent').default);
Vue.component('integration-component', require('./components/IntegrationComponent').default);
Vue.component('facebook-post-component', require("./components/FacebookPostComponent.vue").default);

//Admin

Vue.component('admin-users-component', require('./components/admin/UsersComponent').default);
Vue.component('admin-posts-component', require('./components/admin/PostsComponent').default);
Vue.component('admin-companies-component', require('./components/admin/CompanyComponent').default);
Vue.component('admin-comments-component', require('./components/admin/CommentsComponent').default);
Vue.component('admin-payment-component', require('./components/admin/PaymentComponent').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
