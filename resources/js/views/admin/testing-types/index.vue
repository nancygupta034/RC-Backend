<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Testing Types" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div v-if="displayComponent">
                <Add :typeName="typeName" :id="typeId" @changePage="getData" />
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col">
                    <div class="data-info">
                        Showing {{ types.length }} of {{ totalRecords }} Results
                    </div>
                </div>
            </div>
            <List
                :types="types"
                :totalCount="totalPages"
                @changePage="getData"
                @getRecord="getTestingType"
            />
        </div>
    </section>
</template>

<script>
import { fetchList, fetchTestingType } from "@/api/testingType";
import Add from "@/components/testing-type/Add.vue";
import List from "@/components/testing-type/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "Types",
    components: {
        Add,
        List,
        Breadcrumb,
        LeftSideHeader,
    },
    data() {
        return {
            types: [],
            totalPages: 1,
            totalRecords: 0,
            typeName: "",
            typeId: 0,
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
                    name: "Testing Types",
                },
            ],
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(pageNo = 0) {
            this.typeName = "";
            this.typeId = 0;
            this.listLoading = true;
            fetchList("?skip=" + pageNo).then((response) => {
                this.totalRecords = response.data.totalRecords;
                this.totalPages =
                    response.data.totalRecords > 10
                        ? Math.ceil(response.data.totalRecords / 10)
                        : 1;
                this.types = response.data.data;
            });
        },
        getTestingType(id) {
            this.displayComponent = false;
            fetchTestingType(id).then((response) => {
                this.displayComponent = true;
                if (response.success === true) {
                    this.typeName = response.data.name;
                    this.typeId = response.data.id;
                }
            });
        },
    },
};
</script>
