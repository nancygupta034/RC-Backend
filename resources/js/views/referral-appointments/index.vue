<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Referral Appointments" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div class="row mb-3 g-3 flex-sm-row-reverse align-items-center">
                <div class="col-sm-auto">
                    <div class="data-search">
                        <input
                            type="text"
                            v-model="searchQuery"
                            v-on:input="getData"
                            class="form-control form-control-sm"
                            placeholder="Search by Patient's Name, Referral Dr. Name or Ref No."
                        />
                    </div>
                </div>
                <div class="col-sm-auto"></div>
                <div class="col">
                    <div class="data-info">
                        Showing {{ appointments.length }} of
                        {{ totalAppointments }} Results
                    </div>
                    <div>
                        <small></small>
                    </div>
                </div>
            </div>
            <List
                :appointments="appointments"
                :totalCount="totalPages"
                type="referral"
                @changePage="getData"
            />
        </div>
    </section>
</template>

<script setup>
import List from "@/components/appointment/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
</script>
<script>
import { fetchList } from "@/api/appointment";

export default {
    name: "AppointmentList",
    components: {
        Breadcrumb,
        LeftSideHeader,
    },
    data() {
        return {
            todaysAppointments: 0,
            upcomingAppointments: 0,
            totalAppointments: 0,
            cancelledAppointments: 0,
            appointments: [],
            totalPages: 1,
            filterQuery: "8",
            searchQuery: "",
            breadcrumbs: [
                {
                    class: null,
                    url: "/referral-appointments",
                    name: "Appointments",
                },
                {
                    class: null,
                    url: "/referral-appointments",
                    name: "Referral Appointments",
                },
                {
                    class: "active",
                    url: "",
                    name: "List",
                },
            ],
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(pageNo = 0) {
            this.filterQuery = this.searchQuery !== "" ? 9 : 8;
            this.listLoading = true;
            fetchList(
                `?skip=${pageNo}&filter=${this.filterQuery}&searchQuery=${this.searchQuery}`
            ).then((response) => {
                this.totalAppointments = response.data.totalAppointments;
                this.totalPages =
                    response.data.totalAppointments > 10
                        ? Math.ceil(response.data.totalAppointments / 10)
                        : 1;
                this.appointments = response.data.appointments;
                this.todaysAppointments = response.data.todaysAppointments;
                this.upcomingAppointments = response.data.upcomingAppointments;
                this.cancelledAppointments =
                    response.data.cancelledAppointments;
            });
        },
    },
};
</script>
