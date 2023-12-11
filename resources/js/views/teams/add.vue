<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Add Team" />
                <RightSideButton :btnData="btnData" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Add />
        </div>
    </section>
</template>

<script>
import Add from "@/components/team/Add.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "teams",
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
                    url: "/team-members",
                    name: "Team",
                },
                {
                    class: "active",
                    url: "",
                    name: "Add",
                },
            ],
            btnData: {
                title: "Team Members",
                url: "/team-members",
            },
        };
    },
};
</script>
