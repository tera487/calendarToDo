require('./bootstrap');
window.Vue = require('vue').default;

import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'

const app = new Vue({
    el: '#app',
    router:router,
    store:store,
    vuetify
});