<script setup>
    import Header from '../layout/Header.vue';
    import Sidebar from '../layout/Sidebar.vue';
    import Footer from '../layout/Footer.vue';
    import { useToastr } from '../../toastr.js';

    import { onMounted, ref, computed } from 'vue';
    import axios from 'axios';
    import Swal from 'sweetalert2';
    const toastr = useToastr();

    const selectedStatus = ref();
    const appointments = ref({'data' : []});
    const appointmentStatus = ref([]);
    const getToken = localStorage.getItem("token");
    const getAuthorizationHeader = () => 'Bearer '+getToken;
    const getAppointmentStatus = () => {
        axios.get(
            '/api/v1/appointment-status',
            {
                headers: { "Authorization" : getAuthorizationHeader() }
            },
        )
        .then((response) => {
            appointmentStatus.value = response.data;
        })
    };

    const getAppointments = (status) => {
        selectedStatus.value = status;
        const params = {};
        if (status) {
            params.status = status;
        }
        axios.get(
            '/api/v1/appointments',
            {
                headers: { "Authorization" : getAuthorizationHeader() },
                params: params,
            },
        )
        .then((response) => {
            appointments.value = response.data;
        })
    };

    const appointmentsCount = computed(() => {
        return appointmentStatus.value.map(status => status.count).reduce((acc, value) => acc + value, 0);
    });

    const deleteAppointment = (id) => {
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
                    `/api/v1/appointments/${id}`,
                    {
                        headers: { "Authorization" : getAuthorizationHeader() }
                    }
                )
                .then((response) => {
                    appointments.value.data = appointments.value.data.filter(appointment => appointment.id !== id);
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                });
            }
        })
    };

    const changeStatus = (appointment, status) => {
        axios.patch(
            '/api/v1/appointments/'+appointment.id+'/change-status',
            {
                status : status
            },
            {
                headers: { "Authorization" : getAuthorizationHeader() },
            }
        )
        .then(() => {
            toastr.success('Status change successfully!');
            getAppointmentStatus();
        })
     }

    onMounted(() => {
        getAppointments();
        getAppointmentStatus();
    });
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
                            <h1 class="m-0">Appointments</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Appointments</li>
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
                                    <router-link to="/admin/appointments/create">
                                        <button class="btn btn-primary"><i class="fa fa-plus-circle mr-1"></i> Add New Appointment</button>
                                    </router-link>
                                </div>
                                <div class="btn-group">
                                    <button @click="getAppointments()" type="button" class="btn btn-sm"
                                        :class="[typeof selectedStatus === 'undefined' ? 'btn-secondary' : 'btn-default']">
                                        <span class="mr-1">All</span>
                                        <span class="badge badge-pill badge-info">{{ appointmentsCount }}</span>
                                    </button>

                                    <button v-for="status in appointmentStatus" @click="getAppointments(status.value)" type="button"
                                        class="btn btn-sm" :class="[selectedStatus === status.value ? 'btn-secondary' : 'btn-default']">
                                        <span class="mr-1">{{ status.name }}</span>
                                        <span class="badge badge-pill" :class="`badge-${status.color}`">{{ status.count }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Client Name</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Time</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Options</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="appointments.data.length > 0">
                                            <tr v-for="(appointment, index) in appointments.data" :key="appointment.id">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ appointment.client.first_name }} {{ appointment.client.last_name }}</td>
                                                <td>{{ appointment.start_time }}</td>
                                                <td>{{ appointment.end_time }}</td>
                                                <td>
                                                    <select class="form-control" @change="changeStatus(appointment,$event.target.value)" name="" id="">
                                                        <option :value="status.value" :selected="(appointment.status.value === status.value)" v-for="status in appointmentStatus">{{ status.name }}</option>
                                                    </select>
                                                    <!-- <span class="badge" :class="`badge-${appointment.status.color}`">{{ appointment.status.name }}</span> -->
                                                </td>
                                                <td>
                                                    <router-link :to="`/admin/appointments/${appointment.id}/edit`">
                                                        <i class="fa fa-edit mr-2"></i>
                                                    </router-link>
                                                    <a href="#" @click.prevent="deleteAppointment(appointment.id)">
                                                        <i class="fa fa-trash text-danger"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="6" class="text-center">No result found.</td>
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
</template>
