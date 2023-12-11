<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Add Service" />
                <RightSideButton :btnData="btnData" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Add />
        </div>
    </section>
</template>

<script>
import Add from "@/components/service/Add.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "Services",
    components: {
        Add,
        Breadcrumb,
        RightSideButton,
        LeftSideHeader,
    },
    data() {
        return {
            breadcrumbs: [
                {
                    class: null,
                    url: "/services/list",
                    name: "Services",
                },
                {
                    class: "active",
                    url: "",
                    name: "Add",
                },
            ],
            btnData: {
                title: "All Services",
                url: "/services/list",
            },
        };
    },
};
</script>
