import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from 'vuex-persistedstate'
// import auth from "./modules/auth"; 
import auth from "./auth"; 
import alert from "./modules/alert";
 
Vue.use(Vuex);
 
export default new Vuex.Store({
    plugins:[
        createPersistedState()
    ],
    modules: {
        auth, alert
    }
});