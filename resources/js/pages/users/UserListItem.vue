<script setup>
    import { ref } from 'vue';
    import { useToastr } from '../../toastr.js';

    const toastr = useToastr();

    const props = defineProps({
        user: Object,
        index: Number,
        selectAll: Boolean,
    })
    const emit = defineEmits(['userDeleted','editUser']);
    const userIdBeingDeleted = ref(null);

    const confirmUserDeletion = (id) => {
        userIdBeingDeleted.value = id;
        console.log(userIdBeingDeleted.value);
        $('#deleteUserModal').modal('show');
    };

    const deleteUser = () => {
        // console.log('deleteuser : '+userIdBeingDeleted.value);
        // axios.delete(`/api/users/${userIdBeingDeleted.value}`)
        axios.delete('/api/users/'+userIdBeingDeleted.value)
        .then(() => {
            $('#deleteUserModal').modal('hide');
            toastr.success('User deleted successfully!');
            // users.value.data = users.value.data.filter(user => user.id !== userIdBeingDeleted.value);
            emit('userDeleted',userIdBeingDeleted.value);
        });
    };

    const editUser = (user) => {
        emit('editUser',user);
    }

    const roles = ref([
        {
            name : 'ADMIN',
            value : 1
        },
        {
            name : 'USER',
            value : 2
        }
    ])

     const changeRole = (user, role) => {
        axios.patch('/api/users/'+user.id+'/change-role', {
            role : role,
        })
        .then(() => {
            toastr.success('Role change successfully!');
        })
     }
    const toggleSelection = () => {
        emit('toggleSelection', props.user);
    }
</script>
<template>
    <tr>
        <td><input type="checkbox" :checked="selectAll" @change="toggleSelection" /></td>
        <td>{{ index + 1 }}</td>
        <td>{{ user.name }}</td>
        <td>{{ user.email }}</td>
        <td>{{ user.formatted_created_at }}</td>
        <td>
            <select class="form-control" @change="changeRole(user,$event.target.value)">
                <option :value="role.value" :selected="(user.role === role.name)" v-for="role in roles">{{ role.name }}</option>
            </select>
        </td>
        <td>
            <a href="#" @click.prevent="$emit('editUser', user)">
                <i class="fa fa-edit"></i>
            </a>
            <a href="#" @click.prevent="confirmUserDeletion(user.id)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>
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
