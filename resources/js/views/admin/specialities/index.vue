<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Specialities" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div v-if="displayComponent">
                <Add
                    :specialityName="specialityName"
                    :id="specialityId"
                    @changePage="getData"
                />
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col">
                    <div class="data-info">
                        Showing {{ specialities.length }} of
                        {{ totalRecords }} Results
                    </div>
                </div>
            </div>
            <List
                :specialities="specialities"
                :totalCount="totalPages"
                @changePage="getData"
                @getRecord="getSpeciality"
            />
        </div>
    </section>
</template>

<script>
import { fetchList, fetchSpeciality } from "@/api/speciality";
import Add from "@/components/speciality/Add.vue";
import List from "@/components/speciality/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "Specialities",
    components: {
        Add,
        List,
        Breadcrumb,
        LeftSideHeader,
    },
    data() {
        return {
            specialities: [],
            totalPages: 1,
            totalRecords: 0,
            specialityName: "",
            specialityId: 0,
            displayComponent: true,
            breadcrumbs: [
                {
                    class: null,
                    url: "#",
                    name: "Masters",
                },
                {
                    class: "active",
                    url: "",
                    name: "Specialities",
                },
            ],
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(pageNo = 0) {
            this.specialityName = "";
            this.specialityId = 0;
            this.listLoading = true;
            fetchList("?skip=" + pageNo).then((response) => {
                this.totalRecords = response.data.totalRecords;
                this.totalPages =
                    response.data.totalRecords > 10
                        ? Math.ceil(response.data.totalRecords / 10)
                        : 1;
                this.specialities = response.data.data;
            });
        },
        getSpeciality(id) {
            this.displayComponent = false;
            fetchSpeciality(id).then((response) => {
                this.displayComponent = true;
                if (response.success === true) {
                    this.specialityName = response.data.name;
                    this.specialityId = response.data.id;
                }
            });
        },
    },
};
</script>
