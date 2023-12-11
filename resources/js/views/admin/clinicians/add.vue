<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Add Clinician" />
                <RightSideButton :btnData="btnData" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Add />
        </div>
    </section>
</template>

<script>
import Add from "@/components/clinician/Add.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "Cinicians",
    components: {
        Add,
        Breadcrumb,
        RightSideButton,
        LeftSideHeader,
    },
    data() {
        return {
            btnData: {
                title: "All Clinicians",
                url: "/clinicians/list",
            },
            breadcrumbs: [
                {
                    class: null,
                    url: "/clinicians/list",
                    name: "Our Clinicians",
                },
                {
                    class: "active",
                    url: "",
                    name: "Add New",
                },
            ],
        };
    },
    created() {},
    methods: {},
};
</script>
