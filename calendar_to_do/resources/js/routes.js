import AllProduct from './components/AllProduct.vue';
import CreateProduct from './components/CreateProduct.vue';
import EditProduct from './components/EditProduct.vue';

// todo
import IndexToDo from './components/todos/IndexToDo.vue';
import CreateToDo from './components/todos/CreateToDo.vue';
import EditToDo from './components/todos/EditToDo.vue';
 

import Vue from 'vue';
import VueRouter from 'vue-router';
import store from './store/index';

Vue.use(VueRouter);



export const routes = [
    {
        name: 'home',
        path: '/',
        component: AllProduct
    },
    {
        name: 'create',
        path: '/create',
        component: CreateProduct
    },
    {
        name: 'edit',
        path: '/edit/:id',
        component: EditProduct
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
        path: '/todos/edit/:id',
        component: EditToDo
    },
    
    
];