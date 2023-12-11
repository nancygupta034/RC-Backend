<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Psych Testing" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Add />
        </div>
    </section>
</template>

<script>
import Add from "@/components/testing-request/Data.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "AddPsychTestingT&C",
    components: {
        Add,
        Breadcrumb,
        LeftSideHeader,
    },
    data() {
        return {
            breadcrumbs: [
                {
                    class: "active",
                    url: "",
                    name: "Psych Testing",
                },
            ],
        };
    },
};
</script>
