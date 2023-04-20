<script setup>
    import { ref, onMounted, reactive, handleError } from 'vue';
    import { Form, Field} from 'vee-validate';
    import * as yup from 'yup';
    import { useToastr } from '../../toastr.js';
    import UserListItem from './UserListItem.vue';

    const toastr = useToastr();
    const users = ref([]);
    const editing = ref(false);
    const formValues = ref();
    const form = ref(null);

    const createUserSchema = yup.object({
        name : yup.string().required(),
        email : yup.string().email().required(),
        password : yup.string().required().min(6),
    })
    const editUserSchema = yup.object({
        name: yup.string().required(),
        email: yup.string().email().required(),
        password: yup.string().when((password, schema) => {
            return password ? schema.min(6) : schema;
        }),
    });

    const getUsers = () => {
        axios.get('/api/users')
        .then((response) => {
            users.value = response.data
        })
    };

    const addUser = () => {
        editing.value = false;
        $('#createUserModal').modal('show');
    }

    const createUser = (values,{ resetForm, setErrors }) => {
        axios.post('/api/users', values)
        .then((response) => {
            users.value.unshift(response.data);
            $('#createUserModal').modal('hide');
            resetForm();
            toastr.success('User created successfully!');
        })
        .catch((error) => {
            setErrors(error.response.data.errors);
            // setFieldError('email',error.response.data.errors.email[0]);
            // if (error.response.data.errors) {
            //     setErrors(error.response.data.errors);
            // }
        })
    }

    const editUser = (user) => {
        editing.value = true;
        form.value.resetForm();
        // this.$refs.form.resetForm();
        $('#createUserModal').modal('show');
        formValues.value = {
            id : user.id,
            name : user.name,
            email : user.email
        }
    }

    const updateUser = (values,{ setErrors }) => {
        axios.put('/api/users/'+ formValues.value.id, values)
        .then((response) => {
            // const index = users.value.findIndex(user => user.id == response.data.id);
            const index = users.value.findIndex(user => user.id === response.data.id);
            users.value[index] = response.data;
            $('#createUserModal').modal('hide');
            toastr.success('User updated successfully!');
        }).catch((error) => {
            setErrors(error.response.data.errors);
            console.log(error);
        })
    };

    const handleSubmit = (values, actions) => {
        if(editing.value){
            updateUser(values, actions);
        }else{
            createUser(values, actions);
        }
    }

    const userDeleted = (userId) => {
        users.value = users.value.filter(user => user.id !== userId);
    };


    onMounted(() => {
        getUsers();
    })
</script>
<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <button type="button" class="mb-2 btn btn-primary" @click="addUser">
                Add New User
            </button>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <!-- <th><input type="checkbox" v-model="selectAll" @change="selectAllUsers" /></th> -->
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered Date</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <UserListItem
                                v-for="(user, index) in users"
                                :key="user.id"
                                :user=user
                                :index=index
                                @user-deleted="userDeleted"
                            />
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createUserModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span v-if="editing">Edit User</span>
                        <span v-else>Add User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <Form ref="form" @submit="handleSubmit" :validation-schema="editing ? editUserSchema : createUserSchema" v-slot="{ errors }" :initial-values="formValues">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" :class="{ 'is-invalid':errors.name }" class="form-control " id="name" aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.name }}</span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" :class="{ 'is-invalid':errors.email }" type="email" class="form-control " id="email" aria-describedby="nameHelp" placeholder="Enter full name" />
                            <span class="invalid-feedback">{{ errors.email }}</span>
                        </div>
                        <div class="form-group">
                            <label for="email">Password</label>
                            <Field name="password" :class="{ 'is-invalid' : errors.password }" type="password" class="form-control " id="password" aria-describedby="nameHelp" placeholder="Enter password" />
                            <span class="invalid-feedback">{{ errors.password }}</span>
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
    <div class="modal fade" id="deleteUserModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <span>Delete User</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this user ?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button @click.prevent="deleteUser" type="button" class="btn btn-primary">Delete User</button>
                </div>
            </div>
        </div>
    </div>
</template>
