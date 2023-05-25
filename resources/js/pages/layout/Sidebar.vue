<script setup>
    import { useRouter } from 'vue-router';
    const router = useRouter();

    const logout = () =>{
        localStorage.removeItem("token");
        router.push('/login');
    }
</script>
<template>
    <aside class="main-sidebar elevation-4">
        <a href="#" class="brand-link d-flex justify-content-center">
            <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
            <!-- <span class="brand-text font-weight-light">AdminLTE 3</span> -->
        </a>
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2 admin_profile_photo" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block admin_name">Alexander Pierce</a>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <router-link to="/admin/dashboard" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboar
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/appointments" :class="$route.path.startsWith('/admin/appointments') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>
                                Appointments
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/users" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/categories" :class="$route.path.startsWith('/admin/categories') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-list-alt"></i>
                            <p>
                                Categories
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/product" :class="$route.path.startsWith('/admin/product') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fab fa-product-hunt"></i>
                            <p>
                                Product
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/settings" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Settings
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/profile" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Profile
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item" :class="$route.path.startsWith('/admin/projects') ? 'active' : ''">
                        <a href="#" class="nav-link" :class="$route.path.startsWith('/admin/projects') ? 'active' : ''">
                            <i class="nav-icon fas fa-project-diagram"></i>
                            <p>
                                Projects
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link class="nav-link" to="/admin/projects" :class="$route.path.startsWith('/admin/project-categories/create') ? 'active' : ''">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Project <i class="right fas fa-angle-left"></i>
                                    </p>
                                </router-link>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <router-link to="/admin/projects" active-class="active" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Project Management</p>
                                        </router-link>
                                    </li>
                                    <li class="nav-item">
                                        <router-link class="nav-link" to="/admin/projects/create" :class="$route.path.startsWith('/admin/projects/create') ? 'active' : ''">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add Projects</p>
                                        </router-link>
                                    </li>

                                </ul>
                            </li>
                            <li class="nav-item">
                                <router-link to="/admin/project-categories/create" active-class="active" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Project Categorie</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <router-link to="/admin/chat" :class="$route.path.startsWith('/admin/chat') ? 'active' : ''" class="nav-link">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Chat
                            </p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a @click="logout" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Logout
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>
