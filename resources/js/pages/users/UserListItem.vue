<script setup>
    import { ref } from 'vue';
    import { useToastr } from '../../toastr.js';

    const toastr = useToastr();
    const getToken = localStorage.getItem("token");
    const getAuthorizationHeader = () => 'Bearer '+getToken;

    const props = defineProps({
        user: Object,
        index: Number,
        selectAll: Boolean,
    })
    const emit = defineEmits(['userDeleted','editUser','confirmUserDeletion']);


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
        axios.patch(
            '/api/v1/users/'+user.id+'/change-role',
            {
                role : role
            },
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
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
            <a href="#" @click.prevent="$emit('confirmUserDeletion',user.id)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>

</template>
