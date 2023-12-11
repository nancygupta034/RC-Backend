<template>
    <div class="container login-container">
        <div class="login-logo">
            <img src="../assets/images/logo.png" alt="Responsive Centers" />
        </div>
        <div class="login-wrap">
            <div class="row h-100 align-items-center g-5 mt-1 mt-xl-0">
                <div class="col-12 col-xl d-none d-xl-block">
                    <h1>Responsive Centers <br />for Psychology & Learning</h1>
                </div>
                <div class="col-12 col-xl-auto">
                    <div class="login-box">
                        <h2>Login</h2>
                        <form action="">
                            <div class="form-field">
                                <label for="">Username</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                />
                            </div>
                            <div class="form-field">
                                <label for="">Password</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                />
                            </div>
                            <div class="form-field">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        value=""
                                        id="remember"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="remember"
                                    >
                                        Remember me
                                    </label>
                                </div>
                            </div>
                            <div class="submit-wrap">
                                <router-link
                                    to="/admin"
                                    class="btn w-100 btn-secondary"
                                    >LOGIN
                                </router-link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup></script>
