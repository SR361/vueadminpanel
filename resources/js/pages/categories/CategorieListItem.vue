<script setup>
    import { ref } from 'vue';

    const getToken = localStorage.getItem("token");
    const getAuthorizationHeader = () => 'Bearer '+getToken;

    const props = defineProps({
        categorie: Object,
        index: Number,
        selectAll: Boolean,
    })

    const emit = defineEmits(['editCategorie','confirmCategorieDeletion']);

    const editCategorie = (categorie) => {
        emit('editCategorie',categorie);
    }

    const toggleSelection = () => {
        emit('toggleSelection', props.categorie);
    }

</script>
<template>
    <tr>
        <td><input type="checkbox" :checked="selectAll" @change="toggleSelection" /></td>
        <td>{{ index + 1 }}</td>
        <td>{{ categorie.name }}</td>
        <td>{{ categorie.slug }}</td>

        <td>
            <a href="#" @click.prevent="$emit('editCategorie', categorie)">
                <i class="fa fa-edit"></i>
            </a>
            <a href="#" @click.prevent="$emit('confirmCategorieDeletion',categorie.id)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
            <router-link :to="`/admin/categories/${categorie.slug}/list`">
                <i class="fa fa-eye ml-2"></i>
            </router-link>
        </td>
    </tr>

</template>
