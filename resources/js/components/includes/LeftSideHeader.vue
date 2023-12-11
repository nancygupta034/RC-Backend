<template>
    <div class="col">
        <div class="title">
            <h1>{{headerData}}</h1>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        headerData: {
            type: String,
            required: true,
        }
    },
};
</script>
