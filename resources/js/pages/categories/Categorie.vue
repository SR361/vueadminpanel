<script setup>
    import { ref, onMounted, reactive, watch } from 'vue';
    import { Form, Field} from 'vee-validate';
    import * as yup from 'yup';
    import { useToastr } from '../../toastr.js';
    import { debounce } from 'lodash';
    import { Bootstrap4Pagination } from 'laravel-vue-pagination';
    import CategorieListItem from './CategorieListItem.vue';
    // import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
    // import 'admin-lte/dist/js/adminlte.min.js';
    // import 

    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';

    const toastr = useToastr();
    const categories = ref({'data' : []});
    const editing = ref(false);
    const formValues = ref();
    const form = ref(null);
    const userIdBeingDeleted = ref(null);
    const selectAll = ref(false);
    const selectedCategorie = ref([]);

    const getToken = localStorage.getItem("token");
    const getAuthorizationHeader = () => 'Bearer '+getToken;
    const searchQuery = ref(null);

    const createCategoriesSchema = yup.object({
        name : yup.string().required(),
        slug : yup.string().required(),
    })

    const editCategoriesSchema = yup.object({
        name: yup.string().required(),
        slug: yup.string().required(),
    });

    const getCategories = (page = 1) => {
        axios.get(
            `/api/v1/categories?page=${page}`,
            {
                headers: { "Authorization" : getAuthorizationHeader() }
            },
            {
                params: {
                    query: searchQuery.value
                }
            }
        )
        .then((response) => {
            categories.value = response.data;
        })
    }

    const addCategories = () => {
        editing.value = false;
        $('#createCategoriesModal').modal('show');
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
            '/api/v1/categories', values,
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then((response) => {
            categories.value.data.unshift(response.data);
            $('#createCategoriesModal').modal('hide');
            resetForm();
            toastr.success('Categories created successfully!');
        })
        .catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    }

    const editCategorie = (categorie) => {
        editing.value = true;
        form.value.resetForm();
        // this.$refs.form.resetForm();
        $('#createCategoriesModal').modal('show');
        formValues.value = {
            id : categorie.id,
            name : categorie.name,
            slug : categorie.slug
        }
    }

    const updateCategorie = (values,{ setErrors }) => {
        axios.put(
            '/api/v1/categories/'+ formValues.value.id, values,
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then((response) => {
            const index = categories.value.data.findIndex(categorie => categorie.id === response.data.id);
            categories.value[index] = response.data;
            $('#createCategoriesModal').modal('hide');
            toastr.success('Categorie updated successfully!');
            getCategories();
        }).catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    };

    const deleteCategorie = () => {
        axios.delete(
            '/api/v1/categories/'+userIdBeingDeleted.value,
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then(() => {
            $('#deleteCategorieModal').modal('hide');
            toastr.success('Categorie deleted successfully!');
            categories.value.data = categories.value.data.filter(categorie => categorie.id !== userIdBeingDeleted.value);
        });
    };

    const confirmCategorieDeletion = (id) => {
        userIdBeingDeleted.value = id;
        $('#deleteCategorieModal').modal('show');
    };

    const selectAllCategorie = () => {
        if (selectAll.value) {
            selectedCategorie.value = categories.value.data.map(categorie => categorie.id);
        } else {
            selectedCategorie.value = [];
        }
    }

    const toggleSelection = (categorie) => {
        const index = selectedCategorie.value.indexOf(categorie.id);
        if (index === -1) {
            selectedCategorie.value.push(categorie.id);
        } else {
            selectedCategorie.value.splice(index, 1);
        }
    };

    const bulkDelete = () => {
        axios.delete('/api/v1/categories',
        {
            headers: { "Authorization" : getAuthorizationHeader() },
            data: { ids: selectedCategorie.value }
        })
        .then(response => {
            categories.value.data = categories.value.data.filter(categorie => !selectedCategorie.value.includes(categorie.id));
            selectedCategorie.value = [];
            selectAll.value = false;
            toastr.success(response.data.message);
        });
    };

    watch(searchQuery, debounce(() => {
        getCategories();
    }, 300));

    onMounted(() => {
        getCategories();
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
                    <!-- <div class="d-flex justify-content-between"></div> -->
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <button type="button" class="mb-2 btn btn-primary" @click="addCategories">
                                <i class="fa fa-plus-circle mr-1"></i>
                                Add New Categorie
                            </button>
                            <div v-if="selectedCategorie.length > 0">
                                <button @click="bulkDelete" type="button" class="ml-2 mb-2 btn btn-danger">
                                    <i class="fa fa-trash mr-1"></i>
                                    Delete Selected
                                </button>
                                <span class="ml-2">Selected {{ selectedCategorie.length }} users</span>
                            </div>
                        </div>
                        <div>
                            <input type="text" v-model="searchQuery" class="form-control" placeholder="Search..." />
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" v-model="selectAll" @change="selectAllCategorie" /></th>
                                        <th style="width: 10px">#</th>
                                        <th>Name</th>
                                        <th>slug</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody v-if="categories.data.length > 0">
                                    <CategorieListItem
                                        v-for="(categorie, index) in categories.data"
                                        :key="categorie.id"
                                        :categorie=categorie
                                        :index=index
                                        @editCategorie="editCategorie"
                                        @confirm-categorie-deletion="confirmCategorieDeletion"
                                        @toggle-selection="toggleSelection"
                                        :select-all="selectAll"
                                    />
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="7" class="text-center">No result found...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <Bootstrap4Pagination :data="categories" @pagination-change-page="getCategories" />
                </div>
            </div>
        </div>
        <Footer />
    </div>
    <div class="modal fade" id="createCategoriesModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit Categories</span>
                        <span v-else>Add Categories</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editCategoriesSchema : createCategoriesSchema" v-slot="{ errors }" :initial-values="formValues">
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
                    <h5>Are you sure you want to delete this categorie ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteCategorie" type="button" class="btn btn-primary">Delete Categorie</button>
                </div>
            </div>
        </div>
    </div>
</template>
