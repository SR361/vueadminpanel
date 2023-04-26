<script setup>
    import { ref, onMounted, reactive, watch } from 'vue';
    import { Form, Field} from 'vee-validate';
    import * as yup from 'yup';
    import { useToastr } from '../../../toastr.js';
    // import { debounce } from 'lodash';
    // import { Bootstrap4Pagination } from 'laravel-vue-pagination';

    import Header from '../../layout/Header.vue';
    import Sidebar from '../../layout/Sidebar.vue';
    import Footer from '../../layout/Footer.vue';
    import { useRouter,useRoute } from 'vue-router';
    import ChildCategorieItem from './ChildCategorieItem.vue';

    const toastr = useToastr();
    const childCategories = ref({'data' : []});
    const parentCategories = ref({'data' : []});
    const editing = ref(false)
    const formValues = ref();
    const form = ref(null);
    const catIdBeingDeleted = ref(null);
    const formfield = reactive({
        parent_id: '',
    });

    const getToken = localStorage.getItem("token");
    const getAuthorizationHeader = () => 'Bearer '+getToken;
    const router = useRouter();
    const route = useRoute();

    const createCategoriesSchema = yup.object({
        parent_id : yup.string().required(),
        name : yup.string().required(),
        slug : yup.string().required(),
    })

    const editCategoriesSchema = yup.object({
        // parent_id: yup.string().required(),
        name: yup.string().required(),
        slug: yup.string().required(),
    });

    const addChildCategories = () => {
        editing.value = false;
        const formValues = ref(null);
        $('#createChildCategoriesModal').modal('show');
    }

    const getChildCategories = () => {
        axios.get(
            `/api/v1/child-categorie/${route.params.slug}/list`,
            {
                headers: { "Authorization" : getAuthorizationHeader() }
            },
        )
        .then((response) => {
            childCategories.value = response.data;
        })
    }

    const getParentCategories = () => {
        axios.get(
            `/api/v1/parent-categorie/${route.params.slug}/list`,
            {
                headers: { "Authorization" : getAuthorizationHeader() }
            },
        )
        .then(({data}) => {
            formfield.parent_id = data.id;
        })
    }

    const handleSubmit = (values, actions) => {
        if(editing.value){
            updateCategorie(values, actions);
        }else{
            createCategories(values, actions);
        }
    }

    const createCategories = (values,{ resetForm, setErrors }) => {
        axios.post(
            '/api/v1/child-categories', values,
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then((response) => {
            childCategories.value.data.unshift(response.data);
            $('#createChildCategoriesModal').modal('hide');
            resetForm();
            toastr.success('Chhild categories created successfully!');
        })
        .catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    }

    const editCategorie = (childCategorie) => {
        editing.value = true;
        form.value.resetForm();
        $('#createChildCategoriesModal').modal('show');
        formValues.value = {
            id : childCategorie.id,
            name : childCategorie.name,
            slug : childCategorie.slug
        }
    }

    const updateCategorie = (values,{ resetForm, setErrors }) => {
        axios.put(
            '/api/v1/child-categories/'+ formValues.value.id, values,
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then((response) => {
            const index = childCategories.value.data.findIndex(childCategorie => childCategorie.id === response.data.id);
            childCategories.value[index] = response.data;
            $('#createChildCategoriesModal').modal('hide');
            toastr.success('Child categorie updated successfully!');
            getChildCategories();
        }).catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    };

    const deleteCategorie = () => {
        axios.delete(
            '/api/v1/child-categories/'+catIdBeingDeleted.value,
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then(() => {
            $('#deleteCategorieModal').modal('hide');
            toastr.success('Categorie deleted successfully!');
            categories.value.data = categories.value.data.filter(categorie => categorie.id !== catIdBeingDeleted.value);
        });
    };

    const confirmCategorieDeletion = (id) => {
        catIdBeingDeleted.value = id;
        $('#deleteCategorieModal').modal('show');
    };

    onMounted(() => {
        getChildCategories();
        getParentCategories();
    })
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
                            <h1 class="m-0">Categories</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Categorie</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <button class="btn btn-primary" @click="addChildCategories">
                                        <i class="fa fa-plus-circle mr-1"></i>
                                        Add New Child Categorie
                                    </button>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Slug</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="childCategories.data.length > 0">
                                            <ChildCategorieItem
                                                v-for="(childCategorie, index) in childCategories.data"
                                                :key="childCategorie.id"
                                                :childCategorie=childCategorie
                                                :index=index
                                                @editCategorie="editCategorie"
                                                @confirm-categorie-deletion="confirmCategorieDeletion"
                                            />
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="4" class="text-center">There are not any child categories</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </div>
    <div class="modal fade" id="createChildCategoriesModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit Child Categories</span>
                        <span v-else>Add Child Categories</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editCategoriesSchema : createCategoriesSchema" v-slot="{ errors }" :initial-values="formValues">
                    <div v-if="!editing">
                        <Field v-model="formfield.parent_id"  name="parent_id" type="hidden" id="parent_id" />
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" :class="{ 'is-invalid':errors.name }" class="form-control " id="name" aria-describedby="nameHelp" placeholder="Enter categories name" />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <Field name="slug" :class="{ 'is-invalid':errors.slug }" type="text" class="form-control " id="slug" aria-describedby="nameHelp" placeholder="Enter slug name" />
                            <span class="invalid-feedback">{{ errors.slug }}</span>
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
    <div class="modal fade" id="deleteCategorieModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete Categorie</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this child categorie ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteCategorie" type="button" class="btn btn-primary">Delete Categorie</button>
                </div>
            </div>
        </div>
    </div>
</template>
