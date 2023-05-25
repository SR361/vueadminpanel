<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';

</script>
<template>
    <div class="wrapper">
        <Header />
        <Sidebar />
        <div class="content-wrapper" >
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Project</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Project</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <router-link to="/admin/projects/create">
                                <button type="button" class="mb-2 btn btn-primary" >
                                    <i class="fa fa-plus-circle mr-1"></i>
                                    Add Projects
                                </button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5>Site General Setting</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Project Name</th>
                                        <th>Members</th>
                                        <th>Start Date</th>
                                        <th>Deadline</th>
                                        <th>Client</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>
                                            #
                                        </td>
                                        <td>
                                            <a>
                                                AdminLTE v3
                                            </a>
                                            <br/>
                                            <small>
                                                Created 01.01.2019
                                            </small>
                                        </td>
                                        <td>
                                            <ul class="list-inline">
                                                <li class="list-inline-item">
                                                    <img alt="Avatar" class="table-avatar" src="https://adminlte.io/themes/v3/dist/img/avatar.png">
                                                </li>
                                                <li class="list-inline-item">
                                                    <img alt="Avatar" class="table-avatar" src="https://adminlte.io/themes/v3/dist/img/avatar2.png">
                                                </li>
                                                <li class="list-inline-item">
                                                    <img alt="Avatar" class="table-avatar" src="https://adminlte.io/themes/v3/dist/img/avatar3.png">
                                                </li>
                                                <li class="list-inline-item">
                                                    <img alt="Avatar" class="table-avatar" src="https://adminlte.io/themes/v3/dist/img/avatar4.png">
                                                </li>
                                            </ul>
                                        </td>
                                        <td class="project_progress">
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: 57%">
                                                </div>
                                            </div>
                                            <small>
                                                57% Complete
                                            </small>
                                        </td>
                                        <td class="project-state">
                                            <span class="badge badge-success">Success</span>
                                        </td>
                                        <td class="project-actions text-right">
                                            <a class="btn btn-primary btn-sm" href="#">
                                                <i class="fas fa-folder">
                                                </i>
                                                View
                                            </a>
                                            <a class="btn btn-info btn-sm" href="#">
                                                <i class="fas fa-pencil-alt">
                                                </i>
                                                Edit
                                            </a>
                                            <a class="btn btn-danger btn-sm" href="#">
                                                <i class="fas fa-trash">
                                                </i>
                                                Delete
                                            </a>
                                        </td>
                                    </tr> -->
                                    <tr>
                                        <td align="center" colspan="10">Project data not found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
<style scoped>
    .table-avatar{
        border-radius: 50%;
        display: inline;
        width: 2.5rem;
    }
</style>

