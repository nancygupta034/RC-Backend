<template>
    <div class="section text-center">
        <h1 class="error">403 (Unauthorized)</h1>
        <div class="page">You are not authorized to access this page.</div>
        <a class="back-home" @click.prevent="previous()" href=""
            >Back to home</a
        >
    </div>
</template>

<script>
export default {
    name: "403",
    data() {
        return {};
    },
    methods: {
        previous() {
            window.history.back();
        },
    },
};
</script>
