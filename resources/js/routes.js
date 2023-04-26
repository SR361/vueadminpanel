import Dashboard from './components/Dashboard.vue';
import Login from './components/auth/Login.vue';
import ListAppointments from './pages/appointments/ListAppointments.vue';
import AppointmentForm from './pages/appointments/AppointmentForm.vue';
import UserList from './pages/users/UserList.vue';
import UpdateSetting from './pages/settings/UpdateSetting.vue';
import UpdateProfile from './pages/profile/UpdateProfile.vue';

import Categorie from './pages/categories/Categorie.vue';
import Routes from './routes.js';

export default [
    {
        path : '/login',
        name : 'admin.login',
        component : Login,
        meta : {
            requiresAuth : true
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
    }
]

Router.beforeEach((to,from) => {
    if(to.meta.requiresAuth && !localStorage.getItem('token')){
        return { name : 'admin.login' }
    }
})
