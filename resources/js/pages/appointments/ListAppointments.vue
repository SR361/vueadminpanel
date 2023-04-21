<script setup>
    import { onMounted, ref } from 'vue';
    import axios from 'axios';

    const appointments = ref([]);
    const appointmentStatus = {'SCHEDULED' : 1, 'CONFIRMED' : 2, 'CANCELLED' : 3};
    const getAppointments = (status) => {
        const params = {};
        if (status) {
            params.status = status;
        }
        axios.get('/api/appointments', {
            params: params,
        })
        .then((response) => {
            appointments.value = response.data;
        })
    };

    onMounted(() => {
        getAppointments();
    });
</script>
<template>
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
                            <button class="btn btn-secondary" @click="getAppointments()" type="button">
                                <span class="mr-1">All</span>
                                <span class="badge badge-pill badge-primary"></span>
                            </button>
                            <button class="btn btn-secondary" @click="getAppointments(appointmentStatus.SCHEDULED)" type="button">
                                <span class="mr-1">SCHEDULED</span>
                                <span class="badge badge-pill badge-primary"></span>
                            </button>
                            <button class="btn btn-secondary" @click="getAppointments(appointmentStatus.CONFIRMED)" type="button">
                                <span class="mr-1">CONFIRMED</span>
                                <span class="badge badge-pill badge-success"></span>
                            </button>
                            <button class="btn btn-secondary" @click="getAppointments(appointmentStatus.CANCELLED)" type="button">
                                <span class="mr-1">CANCELLED</span>
                                <span class="badge badge-pill badge-danger"></span>
                            </button>
                            <!-- <button @click="getAppointments()" type="button" class="btn"
                                :class="[typeof selectedStatus === 'undefined' ? 'btn-secondary' : 'btn-default']">
                                <span class="mr-1">All</span>
                                <span class="badge badge-pill badge-info">{{ appointmentsCount }}</span>
                            </button>

                            <button v-for="status in appointmentStatus" @click="getAppointments(status.value)" type="button"
                                class="btn" :class="[selectedStatus === status.value ? 'btn-secondary' : 'btn-default']">
                                <span class="mr-1">{{ status.name }}</span>
                                <span class="badge badge-pill" :class="`badge-${status.color}`">{{ status.count }}</span>
                            </button> -->
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
                                <tbody>
                                    <tr v-for="(appointment, index) in appointments.data" :key="appointment.id">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ appointment.client.first_name }} {{ appointment.client.last_name }}</td>
                                        <td>{{ appointment.start_time }}</td>
                                        <td>{{ appointment.end_time }}</td>
                                        <td>
                                            <span class="badge" :class="`badge-${appointment.status.color}`">{{ appointment.status.name }}</span>
                                        </td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>