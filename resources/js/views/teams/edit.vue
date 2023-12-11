<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Edit Team" />
                <RightSideButton :btnData="btnData" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Edit />
        </div>
    </section>
</template>

<script>
import Edit from "@/components/team/Edit.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "EditTeam",
    components: {
        Edit,
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
                    name: "Edit",
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
