<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Age Ranges" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div v-if="displayComponent">
                <Add
                    :minAge="minAge"
                    :maxAge="maxAge"
                    :id="rangeId"
                    @changePage="getData"
                />
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col">
                    <div class="data-info">
                        Showing {{ ageRanges.length }} of
                        {{ totalRecords }} Results
                    </div>
                </div>
            </div>
            <List
                :ageRanges="ageRanges"
                :totalCount="totalPages"
                @changePage="getData"
                @getRecord="get"
            />
        </div>
    </section>
</template>

<script>
import { fetchList, fetch } from "@/api/ageRange";
import Add from "@/components/age-range/Add.vue";
import List from "@/components/age-range/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "AgeRangeList",
    components: {
        Add,
        List,
        Breadcrumb,
        LeftSideHeader,
    },
    data() {
        return {
            ageRanges: [],
            totalPages: 1,
            totalRecords: 0,
            minAge: null,
            maxAge: null,
            rangeId: 0,
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
                    name: "Age Ranges",
                },
            ],
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(pageNo = 0) {
            this.minAge = null;
            this.maxAge = null;
            this.rangeId = 0;
            this.listLoading = true;
            fetchList("?skip=" + pageNo).then((response) => {
                this.totalRecords = response.data.totalRecords;
                this.totalPages =
                    response.data.totalRecords > 10
                        ? Math.ceil(response.data.totalRecords / 10)
                        : 1;
                this.ageRanges = response.data.data;
            });
        },
        get(id) {
            this.displayComponent = false;
            fetch(id).then((response) => {
                this.displayComponent = true;
                if (response.success === true) {
                    this.minAge = response.data.min_age;
                    this.maxAge = response.data.max_age;
                    this.rangeId = response.data.id;
                }
            });
        },
    },
};
</script>
