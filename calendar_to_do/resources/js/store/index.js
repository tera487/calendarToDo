import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate'
import auth from "./auth"; 
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

Vue.use(Vuex);
 
export default new Vuex.Store({
    plugins:[
        createPersistedState()
    ],
    modules: {
        auth
    }
});