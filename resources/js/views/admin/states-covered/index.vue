<template>
    <section class="section-content bg-white col p-4">
        <nav style="--bs-breadcrumb-divider: '/'" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard"
                        ><i class="icon-home"></i
                    ></router-link>
                </li>
                <li class="breadcrumb-item"><a href="#">Masters</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    States Covered
                </li>
            </ol>
        </nav>
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="States Covered" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div v-if="displayComponent">
                <!-- <Add
                    :stateId="stateId"
                    :id="stateCoveredId"
                    @changePage="getData"
                /> -->
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col">
                    <div class="data-info">{{ totalRecords }} Results</div>
                </div>
            </div>
            <List
                :statesCovered="statesCovered"
                :totalCount="totalPages"
                @changePage="getData"
                @getRecord="get"
            />
        </div>
    </section>
</template>

<script>
import { fetchList } from "@/api/states";
import Add from "@/components/states-covered/Add.vue";
import List from "@/components/states-covered/List.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "Specialities",
    components: {
        Add,
        List,
        LeftSideHeader,
    },
    data() {
        return {
            statesCovered: [],
            totalPages: 1,
            totalRecords: 0,
            stateId: "",
            stateCoveredId: 0,
            displayComponent: true,
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(pageNo = 0) {
            this.stateId = "";
            this.stateCoveredId = 0;
            this.listLoading = true;
            fetchList("?skip=" + pageNo).then((response) => {
                console.log("response => ", response);
                this.totalRecords = response.data.totalRecords;
                this.totalPages =
                    response.data.totalRecords > 10
                        ? Math.ceil(response.data.totalRecords / 10)
                        : 1;
                this.statesCovered = response.data.data;
            });
        },
    },
};
</script>
