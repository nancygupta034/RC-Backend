<template>
    <div class="wrapper clearfix p-3" :class="activeClass">
        <div class="header-main d-lg-none">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="mob-logo">
                            <a href="/dashboard">
                                <img src="../assets/images/logo.png" alt="" />
                            </a>
                        </div>
                    </div>
                    <div class="col-auto ms-auto">
                        <div
                            class="hamburger"
                            :class="hamburgerClass"
                            @click="displaySidebar"
                        >
                            <span class="h-top"></span>
                            <span class="h-middle"></span>
                            <span class="h-bottom"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <main class="main clearfix row mx-0">
            <AdminNavbar />
            <router-view />
        </main>
        <footer class="footer-main section animFade"></footer>
    </div>
</template>

<script setup>
import AdminNavbar from "../components/includes/AdminNavbar.vue";
</script>
<script>
export default {
    data() {
        return {
            activeClass: "",
            hamburgerClass: "",
        };
    },
    methods: {
        displaySidebar() {
            this.hamburgerClass =
                this.hamburgerClass == "h-active" ? "" : "h-active";
            this.activeClass =
                this.activeClass == "menu-active" ? "" : "menu-active";
        },
    },
};
</script>
