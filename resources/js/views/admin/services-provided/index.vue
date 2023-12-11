<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Services Provided" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div v-if="displayComponent">
                <Add
                    :servicesProvidedName="servicesProvidedName"
                    :id="serviceId"
                    @changePage="getData"
                />
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col">
                    <div class="data-info">
                        Showing {{ servicesProvided.length }} of
                        {{ totalRecords }} Results
                    </div>
                </div>
            </div>
            <List
                :servicesProvided="servicesProvided"
                :totalCount="totalPages"
                @changePage="getData"
                @getRecord="get"
            />
        </div>
    </section>
</template>

<script>
import { fetchList, fetch } from "@/api/servicesProvided";
import Add from "@/components/services-provided/Add.vue";
import List from "@/components/services-provided/List.vue";
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
            servicesProvided: [],
            totalPages: 1,
            totalRecords: 0,
            servicesProvidedName: "",
            serviceId: 0,
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
                    name: "Services Provided",
                },
            ],
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(pageNo = 0) {
            this.servicesProvidedName = "";
            this.serviceId = 0;
            this.listLoading = true;
            fetchList("?skip=" + pageNo).then((response) => {
                this.totalRecords = response.data.totalRecords;
                this.totalPages =
                    response.data.totalRecords > 10
                        ? Math.ceil(response.data.totalRecords / 10)
                        : 1;
                this.servicesProvided = response.data.data;
            });
        },
        get(id) {
            this.displayComponent = false;
            fetch(id).then((response) => {
                this.displayComponent = true;
                if (response.success === true) {
                    this.servicesProvidedName = response.data.name;
                    this.serviceId = response.data.id;
                }
            });
        },
    },
};
</script>
