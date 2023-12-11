<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Testing Requests" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div class="row mb-3 g-3 flex-sm-row-reverse align-items-center">
                <div class="col-sm-auto">
                    <div class="data-search">
                        <input
                            type="text"
                            v-model="searchQuery"
                            v-on:input="getData()"
                            class="form-control form-control-sm"
                            placeholder="Search by Name or Email"
                        />
                    </div>
                </div>
                <div class="col">
                    <div class="data-info">
                        Showing {{ testingRequests.length }} of
                        {{ totalRequests }} Results
                    </div>
                    <div>
                        <small></small>
                    </div>
                </div>
            </div>
            <List
                :testingRequests="testingRequests"
                :totalCount="totalPages"
                type="confirmed"
            />
        </div>
    </section>
</template>

<script setup>
import List from "@/components/testing-request/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
</script>
<script>
import { fetchList } from "@/api/testingRequest";
export default {
    name: "TestingRequestList",
    components: {
        Breadcrumb,
        LeftSideHeader,
    },
    data() {
        return {
            totalRequests: 0,
            testingRequests: [],
            totalPages: 1,
            searchQuery: "",
            breadcrumbs: [
                {
                    class: null,
                    url: "/testing-requests",
                    name: "Testing Requests",
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
            this.listLoading = true;
            fetchList(`?skip=${pageNo}&search=${this.searchQuery}`).then(
                (response) => {
                    this.testingRequests = response.data.data;
                    this.totalRequests = response.data.totalRecords;
                    this.totalPages =
                        response.data.totalRecords > 10
                            ? Math.ceil(response.data.totalRecords / 10)
                            : 1;
                }
            );
        },
    },
};
</script>
