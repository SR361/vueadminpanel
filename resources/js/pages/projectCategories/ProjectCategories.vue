<script setup>
    import { ref, onMounted, reactive } from 'vue';
    import * as yup from 'yup';
    import { Form, Field} from 'vee-validate';
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';
    import useProjectCategorie from "./composables/projectscategorie";
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';

    const createProjectCategorieSchema = yup.object({
        category_name : yup.string().required(),
    })

    const editProjectCategorieSchema = yup.object({
        category_name: yup.string().required(),
    });

    const {
        formValues,
        form,
        editing,
        projectcategories,

        handleSubmit,
        addProjectCategorie,
        getProjectCategories,
        editProjectCategorie,
        confirmDeletion,
    } = useProjectCategorie()

    onMounted(() => {
        getProjectCategories()
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
                            <h1 class="m-0">Project Categorie</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Project Categorie</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <button type="button" class="mb-2 btn btn-primary" @click="addProjectCategorie">
                                <i class="fa fa-plus-circle mr-1"></i>
                                Add Project Categorie
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Name</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(projectcategorie,index) in projectcategories.data">
                                        <td>{{ index +1 }}</td>
                                        <td>{{ projectcategorie.category_name }}</td>
                                        <td>
                                            <span class="btn btn-sm btn-success" @click="editProjectCategorie(projectcategorie)">
                                                <i class="fa fa-edit"></i>
                                            </span>
                                            <span class="btn btn-sm btn-danger ml-2" @click="confirmDeletion(projectcategorie)">
                                                <i class="fa fa-trash"></i>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <Bootstrap4Pagination :data="projectcategories" @pagination-change-page="getProjectCategories" />
                </div>
            </div>
        </div>
        <Footer />
    </div>

    <div class="modal fade" id="project-categorie" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="editing" class="modal-title" id="staticBackdropLabel">Add Project Categorie</h5>
                    <h5 v-else class="modal-title" id="staticBackdropLabel">Edit Project Categorie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form"
                    @submit="handleSubmit"
                    :validation-schema="editing ? editProjectCategorieSchema : createProjectCategorieSchema"
                    v-slot="{ errors }"
                    :initial-values="formValues"
                    >
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field type="text" :class="{ 'is-invalid':errors.category_name }" class="form-control " name="category_name" id="category_name" placeholder="Enter a category name" />
                            <span class="invalid-feedback">{{ errors.category_name }}</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </Form>
            </div>
        </div>
</div>

</template>
