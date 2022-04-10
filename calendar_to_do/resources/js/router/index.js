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

// password reset
import ResetPasswordForm from '../components/auth/ResetPasswordForm.vue';
import SendComplete from '../components/auth/SendComplete.vue';

// todo
import IndexToDo from '../components/todos/IndexToDo.vue';
import CreateToDo from '../components/todos/CreateToDo.vue';

// calendar
import IndexCalendar from '../components/calendar/IndexCalendar.vue';


// generalSetting
import showGeneralSetting from '../components/generalSetting/showGeneralSetting.vue';

// account
import IndexUser from '../components/user/IndexUser.vue';

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
    name:"passwordReset",
    path:"/passwordReset/email",
    component:ResetPasswordForm,
    meta:{
        middleware:"guest",
        title:`Register`
    }
  },
  {
    name:"sendComplete",
    path:"/passwordReset/send",
    component:SendComplete,
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
            meta:{middleware:"auth"},
            component: IndexCalendar,
        },
        //accout
        {
            name: 'indexUser',
            path: '/user',
            meta:{middleware:"auth"},
            component: IndexUser,
        },
        // todo
        {
            name: 'indexToDo',
            path: '/todo',
            meta:{middleware:"auth"},
            component: IndexToDo,
        },
        {
            name: 'createToDo',
            path: '/todo/create',
            meta:{middleware:"auth"},
            component: CreateToDo,
        },
        //calendar
        {
            name: 'indexCalendar',
            path: '/calendar',
            meta:{middleware:"auth"},
            component: IndexCalendar,
        },

        //generalSetting
        {
            name: 'showGeneralSetting',
            path: `/generalSetting/:id`,
            meta:{middleware:"auth"},
            component: showGeneralSetting,
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