<template>
    <section class="section-content bg-white col p-4">
        <nav style="--bs-breadcrumb-divider: '/'" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <router-link to="/dashboard"
                        ><i class="icon-home"></i
                    ></router-link>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Change Password
                </li>
            </ol>
        </nav>
        <div class="title-box">
            <div class="row gy-3">
                <div class="col">
                    <div class="title">
                        <h1>Change Password</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrap pt-4">
            <div>
                <div class="form-box mb-3">
                    <vee-form :validation-schema="schema" @submit="submit">
                        <div class="row">
                            <div class="col-12 col-lg-4">
                                <div class="form-field">
                                    <label for=""
                                        >Old Password<sup>*</sup></label
                                    >
                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <span
                                                @click="
                                                    displayOldPassword =
                                                        !displayOldPassword
                                                "
                                            >
                                                <i
                                                    class="bi"
                                                    :class="
                                                        displayOldPassword
                                                            ? 'bi-eye-slash'
                                                            : 'bi-eye'
                                                    "
                                                ></i>
                                            </span>
                                        </div>
                                        <vee-field
                                            name="currentPassword"
                                            :type="
                                                displayOldPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            v-model="currentPassword"
                                            class="form-control"
                                            placeholder="Enter current password"
                                        />
                                    </div>

                                    <div class="error invalid-feedback">
                                        <ErrorMessage
                                            class="error invalid-feedback"
                                            name="currentPassword"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="form-field">
                                    <label for=""
                                        >New Password<sup>*</sup></label
                                    >

                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <span
                                                @click="
                                                    displayNewPassword =
                                                        !displayNewPassword
                                                "
                                            >
                                                <i
                                                    class="bi"
                                                    :class="
                                                        displayNewPassword
                                                            ? 'bi-eye-slash'
                                                            : 'bi-eye'
                                                    "
                                                ></i>
                                            </span>
                                        </div>
                                        <vee-field
                                            name="newPassword"
                                            :type="
                                                displayNewPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            v-model="newPassword"
                                            class="form-control"
                                            placeholder="Enter new password"
                                        />
                                    </div>

                                    <div class="error invalid-feedback">
                                        <ErrorMessage
                                            class="invalid-feedback"
                                            name="newPassword"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="form-field">
                                    <label for=""
                                        >Confirm Password<sup>*</sup></label
                                    >

                                    <div class="input-group">
                                        <div class="input-group-text">
                                            <span
                                                @click="
                                                    displayCPassword =
                                                        !displayCPassword
                                                "
                                            >
                                                <i
                                                    class="bi"
                                                    :class="
                                                        displayCPassword
                                                            ? 'bi-eye-slash'
                                                            : 'bi-eye'
                                                    "
                                                ></i>
                                            </span>
                                        </div>
                                        <vee-field
                                            name="cPassword"
                                            :type="
                                                displayCPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            v-model="cPassword"
                                            class="form-control"
                                            placeholder="Confirm password"
                                        />
                                    </div>

                                    <div class="error invalid-feedback">
                                        <ErrorMessage
                                            class="invalid-feedback"
                                            name="cPassword"
                                        />
                                        {{ cPasswordErr }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="submit-wrap">
                                    <button
                                        type="submit"
                                        class="btn btn-primary-light btn-medium min-small"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            style="margin: 0 10px"
                                            width="24px"
                                            height="24px"
                                            viewBox="0 0 100 100"
                                            preserveAspectRatio="xMidYMid"
                                            v-if="submittingForm"
                                        >
                                            <circle
                                                cx="50"
                                                cy="50"
                                                r="32"
                                                stroke-width="8"
                                                stroke="#3A89C9"
                                                stroke-dasharray="50.26548245743669 50.26548245743669"
                                                fill="none"
                                                stroke-linecap="round"
                                            >
                                                <animateTransform
                                                    attributeName="transform"
                                                    type="rotate"
                                                    repeatCount="indefinite"
                                                    dur="1s"
                                                    keyTimes="0;1"
                                                    values="0 50 50;360 50 50"
                                                ></animateTransform>
                                            </circle>
                                        </svg>
                                        Update
                                    </button>
                                </div>
                            </div>
                        </div>
                    </vee-form>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import store from "../store";
import { updatePassword } from "@/api/auth";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
export default {
    name: "ChangePassword",
    components: {},
    data() {
        return {
            currentPassword: null,
            cPassword: null,
            newPassword: null,
            displayOldPassword: false,
            displayNewPassword: false,
            displayCPassword: false,
            schema: {
                currentPassword: "required|min:8",
                newPassword: "required|min:8",
                cPassword: "required|min:8",
            },
            cPasswordErr: null,
        };
    },
    methods: {
        submit(values, { resetForm }) {
            this.cPasswordErr = null;
            if (this.newPassword != this.cPassword) {
                this.cPasswordErr =
                    "The confirm password field does not match.";
            } else {
                const payload = {
                    old_password: this.currentPassword,
                    new_password: this.newPassword,
                };

                updatePassword(payload)
                    .then(async (response) => {
                        this.submittingForm = false;
                        this.handleResponse(response);
                        if (response.success == true) {
                            resetForm();
                            await store.dispatch("auth/logout");
                            await this.$router.push({ name: "auth.login" });
                        }
                    })
                    .catch((error) => {
                        this.submittingForm = false;
                        this.handleCatch(error);
                    });
            }
        },
        handleResponse(response) {
            const $toast = useToast();
            $toast.open({
                message: response.message,
                type: response.success === true ? "success" : "error",
                position: "top-right",
            });
        },
        handleCatch(error) {
            const $toast = useToast();
            $toast.open({
                message: error.response.data.message,
                type: "error",
                position: "top-right",
            });
        },
    },
};
</script>
