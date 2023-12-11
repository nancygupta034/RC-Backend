<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Users" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div v-if="displayComponent">
                <Add :userData="userData" :id="userId" @changePage="getData" />
            </div>
            <div class="row mb-3 align-items-center">
                <div class="col">
                    <div class="data-info">
                        Showing {{ users.length }} of {{ totalRecords }} Results
                    </div>
                </div>
            </div>
            <List
                :users="users"
                :totalCount="totalPages"
                @changePage="getData"
                @getRecord="getUser"
            />
        </div>
    </section>
</template>

<script>
import { fetchList, fetch } from "@/api/user";
import Add from "@/components/user/Add.vue";
import List from "@/components/user/List.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "Users",
    components: {
        Add,
        List,
        Breadcrumb,
        LeftSideHeader,
    },
    data() {
        return {
            users: [],
            totalPages: 1,
            totalRecords: 0,
            displayComponent: true,
            userData: {},
            userId: 0,
            breadcrumbs: [
                {
                    class: null,
                    url: "#",
                    name: "Masters",
                },
                {
                    class: "active",
                    url: "",
                    name: "Users",
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
            this.userData = {};
            this.userId = 0;
            fetchList("?skip=" + pageNo).then((response) => {
                this.totalRecords = response.data.totalRecords;
                this.totalPages =
                    response.data.totalRecords > 10
                        ? Math.ceil(response.data.totalRecords / 10)
                        : 1;
                this.users = response.data.data;
            });
        },
        getUser(id) {
            this.displayComponent = false;
            fetch(id).then((response) => {
                this.displayComponent = true;
                if (response.success === true) {
                    this.userData = response.data;
                    this.userId = response.data.id;
                }
            });
        },
    },
};
</script>
