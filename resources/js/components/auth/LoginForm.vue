<template>
    <vee-form :validation-schema="schema" @submit="handleLogin">
        <div class="form-field">
            <label for="">Username</label>
            <vee-field
                name="email"
                type="text"
                v-model="loginForm.email"
                class="form-control"
                placeholder="Enter Username"
            />
            <ErrorMessage class="invalid-feedback" name="email" />
        </div>
        <div class="form-field">
            <label for="">Password</label>
            <vee-field
                name="password"
                type="password"
                v-model="loginForm.password"
                class="form-control"
                placeholder="Enter Password"
            />
            <ErrorMessage class="invalid-feedback" name="password" />
        </div>
        <div class="form-field">
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    value=""
                    id="remember"
                />
                <label class="form-check-label" for="remember">
                    Remember me
                </label>
            </div>
        </div>
        <div class="submit-wrap">
            <button type="submit" class="btn w-100 btn-secondary">LOGIN</button>
        </div>
    </vee-form>
</template>
<script>
import axios from "axios";
import store from "../../store";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
export default {
    name: "LoginForm",
    data() {
        return {
            loginForm: {
                email: "",
                password: "",
            },
            loading: false,
            schema: {
                email: "required|email",
                password: "required|min:6",
            },
        };
    },
    methods: {
        async handleLogin() {
            this.loading = true;
            await axios
                .post("/api/auth/login", this.loginForm)
                .then(async (response) => {
                    if (response.data && response.data.success === true) {
                        await store.dispatch("auth/login", response.data.data);
                        await store.dispatch("auth/getUser", {
                            token: response.data.data.token,
                        });
                        if (
                            response.data.data.role &&
                            response.data.data.role.name == "CMS"
                        ) {
                            await this.$router.push({ name: "admin.blogs" });
                        } else {
                            await this.$router.push({
                                name: "admin.dashboard",
                            });
                        }
                    } else {
                        const $toast = useToast();
                        $toast.open({
                            message: response.data.message,
                            type: "error",
                            position: "top-right",
                        });
                    }
                })
                .catch((error) => {
                    if (error.response?.data) {
                        const $toast = useToast();
                        $toast.open({
                            message: error.response.data.message,
                            type: "error",
                            position: "top-right",
                        });
                    }
                });
        },
    },
};
</script>
