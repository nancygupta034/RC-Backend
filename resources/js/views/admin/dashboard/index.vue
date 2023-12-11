<template>
    <section class="section-content bg-white col p-4">
        <nav style="--bs-breadcrumb-divider: '/'" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#"><i class="icon-home"></i></a>
                </li>
            </ol>
        </nav>
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Dashboard" />
                <div class="col-auto h5 fw-normal mb-0 align-self-end">
                    Welcome to Responsive Centers for Psychology & Learning.
                </div>
            </div>
        </div>
        <div class="info-card-wrap py-4 my-2">
            <div class="row gy-4">
                <div class="col-md">
                    <div class="info-card bg-primary">
                        <strong>{{ todaysAppointments }}</strong>
                        <p>Today's <br />Appointments</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="info-card bg-secondary">
                        <strong>{{ upcomingAppointments }}</strong>
                        <p>Upcoming <br />Appointments</p>
                    </div>
                </div>
                <div class="col-md">
                    <div class="info-card bg-danger">
                        <strong>{{ cancelledAppointments }}</strong>
                        <p>Cancelled <br />Appointments</p>
                    </div>
                </div>
            </div>
        </div>
        <List
            :appointments="appointments"
            type="confirmed"
            :totalCount="totalPages"
        />
    </section>
</template>

<script>
import List from "@/components/appointment/List.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
import { fetchList } from "@/api/appointment";
export default {
    name: "Dashboard",
    components: {
        List,
        LeftSideHeader,
    },
    data() {
        return {
            todaysAppointments: 0,
            upcomingAppointments: 0,
            totalAppointments: 0,
            totalPages: 1,
            cancelledAppointments: 0,
            appointments: [],
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(pageNo = 0) {
            this.listLoading = true;
            fetchList(`?skip=${pageNo}&filter=3&searchQuery=`).then(
                (response) => {
                    this.totalAppointments = response.data.totalAppointments;
                    this.totalPages =
                        response.data.totalAppointment > 10
                            ? Math.ceil(response.data.totalAppointment / 10)
                            : 1;
                    this.appointments = response.data.appointments;
                    this.todaysAppointments = response.data.todaysAppointments;
                    this.upcomingAppointments =
                        response.data.upcomingAppointments;
                    this.cancelledAppointments =
                        response.data.cancelledAppointments;
                }
            );
        },
    },
};
</script>
