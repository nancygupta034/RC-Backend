<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="All Blogs" />
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
                            placeholder="Search by Title/Author's Name"
                        />
                    </div>
                </div>
                <div class="col">
                    <div class="data-info">
                        Showing {{ blogs.length }} of {{ totalRecords }} Results
                    </div>
                </div>
            </div>
            <List
                :blogs="blogs"
                :totalCount="totalPages"
                @changePage="getData"
            />
        </div>
    </section>
</template>

<script>
import { fetchList } from "@/api/blog";
import List from "@/components/blog/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "BlogList",
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
                url: "/blogs/add",
            },
            blogs: [],
            searchQuery: "",
            totalPages: 1,
            totalRecords: 0,
            breadcrumbs: [
                {
                    class: null,
                    url: "/blogs/list",
                    name: "Blogs",
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
                    this.blogs = response.data.data;
                }
            );
        },
    },
};
</script>
