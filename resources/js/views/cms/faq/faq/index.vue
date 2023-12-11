<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="All FAQ" />
                <RightSideButton :btnData="btnData" />
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
                            placeholder="Search by Question"
                        />
                    </div>
                </div>
                <div class="col">
                    <div class="data-info">
                        Showing {{ faqs.length }} of {{ totalRecords }} Results
                    </div>
                </div>
            </div>
            <List
                :faqs="faqs"
                :totalCount="totalPages"
                @changePage="getData"
                @getRecord="get"
            />
        </div>
    </section>
</template>

<script>
import { fetchList, fetch } from "@/api/faq";
import List from "@/components/faq/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "FaqList",
    components: {
        List,
        Breadcrumb,
        RightSideButton,
        LeftSideHeader,
    },
    data() {
        return {
            btnData: {
                title: "Add New",
                url: "/faqs/add",
            },
            faqs: [],
            searchQuery: "",
            totalPages: 1,
            totalRecords: 0,
            displayComponent: true,
            breadcrumbs: [
                {
                    class: null,
                    url: "/faqs/list",
                    name: "Faq",
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
                    this.totalRecords = response.data.totalRecords;
                    this.totalPages =
                        response.data.totalRecords > 10
                            ? Math.ceil(response.data.totalRecords / 10)
                            : 1;
                    this.faqs = response.data.data;
                }
            );
        },
        get(id) {
            this.displayComponent = false;
            fetch(id).then((response) => {
                this.displayComponent = true;
                if (response.success === true) {
                }
            });
        },
    },
};
</script>
