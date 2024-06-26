import './bootstrap';

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import '/node_modules/jquery/dist/jquery.js';
import '/node_modules/vue3-select2-component/dist/Select2.min.js';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createRouter, createWebHistory } from 'vue-router';
import Select2 from 'vue3-select2-component';

import Dashboard from './components/Dashboard.vue';
import Login from './components/auth/Login.vue';
import ListAppointments from './pages/appointments/ListAppointments.vue';
import AppointmentForm from './pages/appointments/AppointmentForm.vue';
import UserList from './pages/users/UserList.vue';
import UpdateSetting from './pages/settings/UpdateSetting.vue';
import UpdateProfile from './pages/profile/UpdateProfile.vue';
import Chat from './pages/chat/Chat.vue';

import ChildCategorieList from './pages/categories/ChildCategorie/ChildCategorieList.vue';
import Products from './pages/product/ProductList.vue';
import ProductCreate from './pages/product/ProductCreate.vue';
import Categorie from './pages/categories/Categorie.vue';

import Projects from './pages/projects/Projects.vue';
import ProjectsCreate from './pages/projects/ProjectsCreate.vue';
import ProjectCategories from './pages/projectCategories/ProjectCategories.vue';

const routes = [
    {
        path : '/login',
        name : 'admin.login',
        component : Login,
        meta : {
            requiresAuth : false
        }
    },
    {
        path : '/admin/dashboard',
        name : 'admin.dashboard',
        component : Dashboard,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/appointments',
        name : 'admin.appointments',
        component : ListAppointments,
        meta : {
            requiresAuth : true
        }
    },
    {
        path: '/admin/appointments/create',
        name: 'admin.appointments.create',
        component: AppointmentForm,
        meta : {
            requiresAuth : true
        }
    },
    {
        path: '/admin/appointments/:id/edit',
        name: 'admin.appointments.edit',
        component: AppointmentForm,
        meta : {
            requiresAuth : true
        }
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSetting,
        meta : {
            requiresAuth : true
        }
    },
    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile,
        meta : {
            requiresAuth : true
        }
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: UserList,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/categories',
        name : 'admin.categories',
        component : Categorie,
        meta : {
            requiresAuth : true
        }
    },
    {
        path: '/admin/categories/:slug/list',
        name: 'admin.categories.list',
        component: ChildCategorieList,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/product',
        name : 'admin.product',
        component : Products,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/product/create',
        name : 'admin.product.create',
        component : ProductCreate,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/product/:id/edit',
        name : 'admin.product.edit',
        component : ProductCreate,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/chat',
        name : 'admin.chat',
        component : Chat,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/projects',
        name : 'admin.projects',
        component : Projects,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/projects/create',
        name : 'admin.projects.create',
        component : ProjectsCreate,
        meta : {
            requiresAuth : true
        }
    },
    {
        path : '/admin/project-categories/create',
        name : 'admin.project.categories.create',
        component : ProjectCategories,
        meta : {
            requiresAuth : true
        }
    }
];

const router = createRouter({
    routes: routes,
    history: createWebHistory(),
});

router.beforeEach((to,from) => {
    if(to.meta.requiresAuth && !localStorage.getItem('token')){
        return { name : 'admin.login' }
    }
    if(to.meta.requiresAuth == false && localStorage.getItem('token')){
        return { name : 'admin.dashboard' }
    }
})
// Vue.prototype.jQuery = jQuery
// window.jQuery = jQuery

const app = createApp({});
export default router;
app.use(router);
// app.use(jQuery);
app.component('Select2',Select2);
app.mount('#app');
