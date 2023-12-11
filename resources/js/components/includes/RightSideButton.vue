<template>
    <div class="col-auto">
        <router-link
            :to="btnData.url"
            class="btn btn-medium btn-primary-light"
            >{{btnData.title}}</router-link
        >
    </div>
</template>
<script>
export default {
    props: {
        btnData: {
            type: Object,
            required: true,
        }
    },
};
</script>
