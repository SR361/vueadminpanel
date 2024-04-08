<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';
    import { reactive,ref, onMounted } from 'vue';
    import useProject from "./composables/projects";
    // import Select2 from 'select2';
    // import Select2 from 'v-select2-component';
    import * as yup from 'yup';
    import { Form, Field, ErrorMessage} from 'vee-validate';

    // const optionsSelected = ref([]);
    // const options1 = [
    //     { 'id' : 1, 'text' : "1-1"}, 
    //     { 'id' : 2, 'text' : "1-2"}, 
    //     { 'id' : 3, 'text' : "1-3"}, 
    //     { 'id' : 4, 'text' : "1-4"}, 
    //     { 'id' : 5, 'text' : "1-5"},
    //     { 'id' : 6, 'text' : "1-6"}
    // ];
    // const mySelectEvent = ($event) => {
    //     const index = optionsSelected.value.indexOf($event.id);
        
    //     if (index === -1) {
    //         optionsSelected.value.push($event.id.id);
    //     } else {
    //         optionsSelected.value.splice($event.id, 1);
    //     }
    //     console.log(optionsSelected.value);
    // };

    const createProjectSchema = yup.object({
        // short_code      : yup.string().required().label('Short code'),
        // project_name    : yup.string().required().label('Project name'),
        // start_date      : yup.string().required().label('Start date'),
        // deadline        : yup.string().required(),
        // // project_member  : yup.string().min(1).required().label('⛔️ Project member'),
        // category_id     : yup.string().required().label('Category'),
        // client_id       : yup.string().required().label('Client'),
        // currency_id     : yup.string().required().label('Currency'),
        // project_budget  : yup.string().required().label('Project budget'),
        // hours_allocated : yup.string().required().label('Hours allocated'),
    })

    const editProjectSchema = yup.object({
        category_name: yup.string().required(),
    });

    const memberSelected = ref([]);
    const SelectMemberEvent = ($event) => {
        const index = optionsSelected.value.indexOf($event.id);
        
        if (index === -1) {
            optionsSelected.value.push($event.id.id);
        } else {
            optionsSelected.value.splice($event.id, 1);
        }
        console.log(optionsSelected.value);
    };

    const {
        projectcategories,
        clients,
        users,
        currencies,
        formValues,
        editing,

        getProjectCategories,
        getClients,
        getUsers,
        getCurrencies,
        handleSubmit
    } = useProject()

    onMounted(() => {
        getProjectCategories()
        getClients()
        getUsers()
        getCurrencies()

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
                            <h1 class="m-0">Projects</h1>
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
                        <Form ref="form"
                            @submit="handleSubmit"
                            :validation-schema="editing ? editProjectSchema : createProjectSchema"
                            v-slot="{  meta,errors }"
                            :initial-values="formValues"
                            >
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Short Code</label>
                                            <Field type="text" :class="{ 'is-invalid':errors.short_code }" name="short_code" class="form-control" placeholder="Project unique short code" />
                                            <span class="invalid-feedback">⛔️ {{ errors.short_code }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="client">Project Name</label>
                                            <Field :class="{ 'is-invalid':errors.project_name }" type="text" name="project_name" class="form-control" placeholder="Write a project name" />
                                            <span class="invalid-feedback">⛔️ {{ errors.project_name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Start Date</label>
                                            <Field type="date" :class="{ 'is-invalid':errors.start_date }" name="start_date" class="form-control flatpickr" placeholder="Select Date" />
                                            <span class="invalid-feedback">⛔️ {{ errors.start_date }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Deadline</label>
                                            <Field type="date" :class="{ 'is-invalid':errors.deadline }" name="deadline" class="form-control flatpickr" placeholder="Select Date" />
                                            <span class="invalid-feedback">⛔️ {{ errors.deadline }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Add Project Members</label>
                                            <Select2 v-model="memberSelected" :options="users" id = "project_member"
                                            :settings="{ placeholder: 'Project member', width: '50%', multiple: true }" 
                                            @select="SelectMemberEvent($event)"/>
                                            <!-- <Select2 v-model="optionsSelected" :options="options1" 
                                                :settings="{ placeholder: 'Specifies the placeholder through settings', width: '50%', multiple: true }" 
                                                @select="mySelectEvent($event)"/>
                                            <ErrorMessage class="text-danger" name="project_member" /> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Project Category</label>
                                            <Field :class="{ 'is-invalid':errors.category_id }" as="select" name="category_id" class="form-control">
                                                <option :value="projectcategorie.id" v-for="projectcategorie in projectcategories">{{ projectcategorie.category_name }}</option>
                                            </Field>
                                            <span class="invalid-feedback">⛔️ {{ errors.category_id }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Client</label>
                                            <Field as="select" :class="{ 'is-invalid':errors.client_id }" name="client_id" id="" class="form-control">
                                                <option value="">---</option>
                                                <option :value="client.id" v-for="client in clients">{{ client.first_name }} {{ client.last_name }}</option>
                                            </Field>
                                            <span class="invalid-feedback">⛔️ {{ errors.client_id }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Project Summary</label>
                                            <Field as="textarea" name="project_summary" class="form-control" rows="5" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Notes</label>
                                            <Field as="textarea" name="notes" class="form-control" rows="5" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Currency</label>
                                            <Field as="select" :class="{ 'is-invalid':errors.currency_id }" name="currency_id" id="" class="form-control" >
                                                <option value="0">--</option>
                                                <option :value="currencie.id" v-for="currencie in currencies"> {{ currencie.currency_code }}</option>
                                            </Field>
                                            <span class="invalid-feedback">⛔️ {{ errors.currency_id }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Project Budget</label>
                                            <Field type="number" :class="{ 'is-invalid':errors.project_budget }" name="project_budget" class="form-control" placeholder="e.g. 10000" />
                                            <span class="invalid-feedback">⛔️ {{ errors.project_budget }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Hours Estimate (In Hours)</label>
                                            <Field :class="{ 'is-invalid':errors.hours_allocated }" name="hours_allocated" type="number" class="form-control" placeholder="e.g. 500" />
                                            <span class="invalid-feedback">⛔️ {{ errors.hours_allocated }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>