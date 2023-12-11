<template>
    <nav style="--bs-breadcrumb-divider: '/'" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link to="/dashboard"
                    ><i class="icon-home"></i
                ></router-link>
            </li>
            <li
                v-for="(crumb, ci) in crumbs"
                :key="ci"
                class="breadcrumb-item"
                :class="crumb.class"
            >
                <a :href="crumb.url" v-if="crumb.class != 'active'">{{
                    crumb.name
                }}</a>
                {{ crumb.class == 'active' ? crumb.name : '' }}
            </li>
        </ol>
    </nav>
</template>
<script>
export default {
    props: {
        crumbs: {
            type: Array,
            required: true,
        },
    },
};
</script>
