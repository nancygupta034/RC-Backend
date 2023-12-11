<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Add Blog" />
                <RightSideButton :btnData="btnData" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Add />
        </div>
    </section>
</template>

<script>
import Add from "@/components/blog/Add.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "AddBlog",
    components: {
        Add,
        Breadcrumb,
        RightSideButton,
        LeftSideHeader,
    },
    data() {
        return {
            btnData: {
                title: "All Blogs",
                url: "/blogs/list",
            },
            breadcrumbs: [
                {
                    class: null,
                    url: "/blogs/list",
                    name: "Blogs",
                },
                {
                    class: "active",
                    url: "",
                    name: "Add",
                },
            ],
        };
    },
};
</script>
