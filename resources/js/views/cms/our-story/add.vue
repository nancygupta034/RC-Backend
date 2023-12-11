<template>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Our Story" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Add />
        </div>
    </section>
</template>

<script>
import Add from "@/components/our-story/Add.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
export default {
    name: "AddOurStory",
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
                    name: "Our Story",
                },
            ],
        };
    },
};
</script>
