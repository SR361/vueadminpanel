import { ref, onMounted, reactive } from 'vue';
import axios from "axios";
import { useRouter,useRoute } from 'vue-router';
import { useToastr } from '../../../toastr.js';

export default function useCompanies() {
    const projectcategories = ref()
    const clients = ref();
    const users = ref();
    const currencies = ref();
    const formValues = ref();
    const editing = ref(false);
    const toastr = useToastr();
    const router = useRouter();
    const route = useRoute();

    const config = {
        headers: {
            "Authorization" : 'Bearer '+localStorage.getItem("token")
        }
    };

    const getProjectCategories = async () => {
        let response = await axios.get(`/api/v1/project-categorie/all`,config)
        projectcategories.value = response.data;
    }
    const getClients = async () => {
        let response = await axios.get(`/api/v1/clients`,config)
        clients.value = response.data;
    }
    const getUsers = async () => {
        let response = await axios.get(`/api/v1/allusers`,config)
        users.value = response.data;
    }

    const getCurrencies = async () => {
        let response = await axios.get(`/api/v1/currencie`,config)
        currencies.value = response.data;
    }

    const handleSubmit = (values, actions) => {
        if(editing.value){
            updateProject(values, actions);
        }else{
            storeProject(values, actions);
        }
    }

    const storeProject = async (values,{ resetForm, setErrors })=>{
        console.log(values);
        await axios.post('/api/v1/projects/create', values,config)
        .then((response) => {
            // router.push({name: 'admin.projects'})
            router.push('/admin/projects');
            toastr.success('Project created successfully!');
        })
        .catch((error) => {
            if (error.response) {
                setErrors(error.response.data.errors);
            }
        })
    }

    const updateProject = async () => {

    }

    return {
        projectcategories,
        clients,
        users,
        currencies,
        formValues,

        getProjectCategories,
        getClients,
        getUsers,
        getCurrencies,
        handleSubmit,
        storeProject,
        updateProject
    }
}
