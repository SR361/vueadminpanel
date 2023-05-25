import { ref, onMounted, reactive } from 'vue';

import axios from "axios";
import { useRouter } from 'vue-router';
import { useToastr } from '../../../toastr.js';
import Swal from 'sweetalert2';

export default function useProjectCategorie() {
    const toastr = useToastr();
    const projectcategories = ref({'data' : []})
    const router = useRouter()
    const errors = ref('')
    const editing = ref(false);
    const formValues = ref();
    const form = ref(null);

    const config = {
        headers: {
            "Authorization" : 'Bearer '+localStorage.getItem("token")
        }
    };

    const handleSubmit = (values, actions) => {
        if(editing.value){
            updateProjectCategorie(values, actions);
        }else{
            storeProjectCategorie(values, actions);
        }
    }

    const addProjectCategorie = () => {
        editing.value = false;
        $('#category_name').val('');
        $('#project-categorie').modal('show');
    }

    const getProjectCategories = async (page = 1) => {
        let response = await axios.get(`/api/v1/project-categorie?page=${page}`,config)
        projectcategories.value = response.data;
    }

    const storeProjectCategorie = async (values,{ resetForm, setErrors }) => {
        await axios.post('/api/v1/project-categorie/create', values,config)
        .then((response) => {
            projectcategories.value.data.unshift(response.data);
            $('#project-categorie').modal('hide');
            resetForm();
            toastr.success('Project categorie created successfully!');
        })
        .catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    }

    const editProjectCategorie = async (categorie) => {
        editing.value = true;
        formValues.value = {
            id              : categorie.id,
            category_name   : categorie.category_name,
        }
        formValues.category_name = categorie.category_name;
        $('#project-categorie').modal('show');
    }

    const updateProjectCategorie = async (values,{ resetForm,setErrors }) => {

        await axios.put('/api/v1/project-categorie/'+ formValues.value.id, values,config)
        .then((response) => {
            const index = projectcategories.value.data.findIndex(projectcategorie => projectcategorie.id === response.data.id);

            projectcategories.value.data[index] = response.data;
            $('#project-categorie').modal('hide');
            resetForm();
            toastr.success('Project categorie updated successfully!');
        }).catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    }

    const confirmDeletion = async (categorie) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(
                    `/api/v1/project-categorie/delete/${categorie.id}`,config
                )
                .then((response) => {
                    projectcategories.value.data = projectcategories.value.data.filter(projectcategorie => projectcategorie.id !== categorie.id);
                    Swal.fire(
                        'Deleted!',
                        'Your product categorie has been deleted.',
                        'success'
                    )
                });
            }
        })
    }

    return {
        formValues,
        form,
        projectcategories,
        errors,
        editing,

        handleSubmit,
        addProjectCategorie,
        getProjectCategories,
        storeProjectCategorie,
        editProjectCategorie,
        updateProjectCategorie,
        confirmDeletion,
    }
}
