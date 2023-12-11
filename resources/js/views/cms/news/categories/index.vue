<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Categories" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div v-if="displayComponent">
                <Add
                    :blogCategoryName="blogCategoryName"
                    :id="blogCategoryId"
                    type="news"
                    @changePage="getData"
                />
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col">
                    <div class="data-info">
                        Showing {{ blogCategories.length }} of
                        {{ totalRecords }} Results
                    </div>
                </div>
            </div>
            <List
                :blogCategories="blogCategories"
                :totalCount="totalPages"
                @changePage="getData"
                @getRecord="getBlogCategory"
            />
        </div>
    </section>
</template>

<script>
import { fetchList, fetchBlogCategory } from "@/api/category";
import Add from "@/components/category/Add.vue";
import List from "@/components/category/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "Categories",
    components: {
        Add,
        List,
        Breadcrumb,
        LeftSideHeader,
    },
    data() {
        return {
            blogCategories: [],
            totalPages: 1,
            totalRecords: 0,
            blogCategoryName: "",
            blogCategoryId: 0,
            displayComponent: true,
            breadcrumbs: [
                {
                    class: null,
                    url: "/news/list",
                    name: "News/Events",
                },
                {
                    class: "active",
                    url: "",
                    name: "Categories",
                },
            ],
        };
    },
    created() {
        this.getData();
    },
    methods: {
        getData(pageNo = 0) {
            this.blogCategoryName = "";
            this.blogCategoryId = 0;
            this.listLoading = true;
            fetchList("?type=news&skip=" + pageNo).then((response) => {
                this.totalRecords = response.data.totalRecords;
                this.totalPages =
                    response.data.totalRecords > 10
                        ? Math.ceil(response.data.totalRecords / 10)
                        : 1;
                this.blogCategories = response.data.data;
            });
        },
        getBlogCategory(id) {
            this.displayComponent = false;
            fetchBlogCategory(id).then((response) => {
                this.displayComponent = true;
                if (response.success === true) {
                    this.blogCategoryName = response.data.name;
                    this.blogCategoryId = response.data.id;
                }
            });
        },
    },
};
</script>
