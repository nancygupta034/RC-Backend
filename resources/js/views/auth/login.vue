<template>
    <div class="container login-container">
        <div class="login-logo">
            <img src="../../assets/images/logo.png" alt="Responsive Centers" />
        </div>
        <div class="login-wrap">
            <div class="row h-100 align-items-center g-5 mt-1 mt-xl-0">
                <div class="col-12 col-xl d-none d-xl-block">
                    <h1>Responsive Centers <br />for Psychology & Learning</h1>
                </div>
                <div class="col-12 col-xl-auto">
                    <div class="login-box">
                        <h2>Login</h2>
                        <LoginForm />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import LoginForm from "@/components/auth/LoginForm.vue";
</script>
