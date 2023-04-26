<script setup>
    const props = defineProps({
        childCategorie: Object,
        index: Number,
    })

    const emit = defineEmits(['editCategorie','confirmCategorieDeletion']);

    const editCategorie = (childCategorie) => {
        emit('editCategorie',childCategorie);
    }
</script>
<template>
    <tr >
        <td>{{ index + 1 }}</td>
        <td>{{ childCategorie.name }}</td>
        <td>{{ childCategorie.slug }}</td>
        <td>
            <a href="#" @click.prevent="$emit('editCategorie', childCategorie)">
            <!-- <a href=""> -->
                <i class="fa fa-edit"></i>
            </a>
            <a href="#" @click.prevent="$emit('confirmCategorieDeletion',childCategorie.id)">
                <i class="fa fa-trash text-danger ml-2"></i>
            </a>
        </td>
    </tr>
</template>
