<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';
    import { onMounted } from 'vue';
    import useProject from "./composables/projects";
    import Select2 from 'select2';
    import $ from "jquery";

    const {
        projectcategories,
        clients,
        users,
        currencies,

        getProjectCategories,
        getClients,
        getUsers,
        getCurrencies
    } = useProject()

    onMounted(() => {
        getProjectCategories()
        getClients()
        getUsers()
        getCurrencies()

        Select2('.project_member')
        setTimeout(() => {
            $('#project_member').select2({
                placeholder: "Select a option",
            })
        }, 1000)
        flatpickr(".flatpickr", {
            enableTime: false,
            dateFormat: "Y-m-d",
        })
    });


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
                            <h1 class="m-0">Projects {{ company }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Projects Create</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h5>Projects Create</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Short Code</label>
                                        <input type="text" class="form-control" id="title" placeholder="Project unique short code">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="client">Project Name</label>
                                        <input type="text" class="form-control" placeholder="Write a project name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Start Date</label>
                                        <input type="date" class="form-control flatpickr" placeholder="Select Date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Deadline</label>
                                        <input type="date" class="form-control flatpickr" placeholder="Select Date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Add Project Members</label>
                                        <select name="" class="form-control select2" id="project_member" multiple style="width: 100%;">
                                            <option v-for="user in users" value="{{ user.id }}" >{{ user.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Project Category</label>
                                        <select class="form-control">
                                            <option value="1" v-for="projectcategorie in projectcategories">{{ projectcategorie.category_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Client</label>
                                        <select name="" id="" class="form-control">
                                            <option value="">---</option>
                                            <option value="{{ client.id }}" v-for="client in clients">{{ client.first_name }} {{ client.last_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Project Summary</label>
                                        <textarea name="" class="form-control" rows="5" ></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Notes</label>
                                        <textarea name="" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Currency</label>
                                        <select name="" id="" class="form-control">
                                            <option value="0">--</option>
                                            <option value="{{ currencie.id }}" v-for="currencie in currencies"> {{ currencie.currency_code }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Project Budget</label>
                                        <input type="text" class="form-control" placeholder="e.g. 10000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Hours Estimate (In Hours)</label>
                                        <input type="text" class="form-control" placeholder="e.g. 500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>
