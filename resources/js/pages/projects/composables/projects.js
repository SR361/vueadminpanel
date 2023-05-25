import { ref } from 'vue'
import axios from "axios";
// import { useRouter } from 'vue-router';

export default function useCompanies() {
    const projectcategories = ref()
    const clients = ref();
    const users = ref();
    const currencies = ref();
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

    return {
        projectcategories,
        clients,
        users,
        currencies,

        getProjectCategories,
        getClients,
        getUsers,
        getCurrencies,
    }
}
