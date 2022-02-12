import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'

Vue.use(VueRouter)

/* Guest Component */
const Login = () => import('../components/auth/Login.vue' /* webpackChunkName: "resource/js/components/login" */)
const Register = () => import('../components/auth/Register.vue' /* webpackChunkName: "resource/js/components/register" */)
/* Guest Component */

/* Layouts */
const DahboardLayout = () => import('../components/Layouts/Dashboard.vue' /* webpackChunkName: "resource/js/components/layouts/dashboard" */)
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('../components/auth/Dashboard.vue' /* webpackChunkName: "resource/js/components/dashboard" */)
/* Authenticated Component */

// todo
import IndexToDo from '../components/todos/IndexToDo.vue';
import CreateToDo from '../components/todos/CreateToDo.vue';

// calendar
import IndexCalendar from '../components/calendar/IndexCalendar.vue';

// グローバル登録

// フォーム
Vue.component('BaseInput', require('../components/form/BaseInput.vue').default);
Vue.component('BaseTextarea', require('../components/form/BaseTextarea.vue').default);

// エラー
Vue.component('ErrorRequired', require('../components/error/ErrorRequired.vue').default);

const Routes = [
  {
    name:"login",
    path:"/login",
    component:Login,
    meta:{
        middleware:"guest",
        title:`Login`
    }
  },
  {
    name:"register",
    path:"/register",
    component:Register,
    meta:{
        middleware:"guest",
        title:`Register`
    }
  },
  {
    path:"/",
    component:DahboardLayout,
    meta:{
        middleware:"auth"
    },
    children:[
        {
            name:"dashboard",
            path: '/',
            component: Dashboard,
            meta:{
                title:`Dashboard`
            }
        },
        // todo
        {
            name: 'indexToDo',
            path: '/todos',
            component: IndexToDo,
        },
        {
            name: 'createToDo',
            path: '/todos/create',
            component: CreateToDo,
        },
        {
            name: 'editToDo',
            path: '/todos/:id',
            component: CreateToDo,
        },
        //calendar
        {
            name: 'IndexCalendar',
            path: '/calendar',
            component: IndexCalendar,
        },

    ]
  },
]

var router  = new VueRouter({
  mode: 'history',
  routes: Routes
})

router.beforeEach((to, from, next) => {
  document.title = `${to.meta.title} - ${process.env.MIX_APP_NAME}`
  if(to.meta.middleware=="guest"){
      if(store.state.auth.authenticated){
          next({name:"dashboard"})
      }
      next()
  }else{
      if(store.state.auth.authenticated){
          next()
      }else{
          next({name:"login"})
      }
  }
})

export default router