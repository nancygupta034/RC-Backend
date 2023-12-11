<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Edit News" />
                <RightSideButton :btnData="btnData" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Edit />
        </div>
    </section>
</template>

<script>
import Edit from "@/components/news/Edit.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "EditNews",
    components: {
        Edit,
        Breadcrumb,
        RightSideButton,
        LeftSideHeader,
    },
    data() {
        return {
            btnData: {
                title: "All News",
                url: "/news/list",
            },
            breadcrumbs: [
                {
                    class: null,
                    url: "/news/list",
                    name: "News",
                },
                {
                    class: "active",
                    url: "",
                    name: "Edit",
                },
            ],
        };
    },
};
</script>
