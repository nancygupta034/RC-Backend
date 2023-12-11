<template>
    <div class="form-box mb-3">
        <vee-form :validation-schema="schema" @submit="submit">
            <div class="row">
                <div class="col-12 col-xl-3">
                    <div class="form-field">
                        <vee-field
                            name="name"
                            type="text"
                            v-model="name"
                            class="form-control"
                            placeholder="Enter Name"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="error invalid-feedback"
                                name="name"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="form-field">
                        <vee-field
                            name="email"
                            type="text"
                            v-model="email"
                            class="form-control"
                            placeholder="Enter Email"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="email"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-3">
                    <div class="form-field">
                        <vee-field
                            name="role_id"
                            as="select"
                            v-model="role_id"
                            class="form-select"
                        >
                            <option value="">Select Role</option>
                            <option
                                v-for="(role, index) in roles"
                                :key="index"
                                :value="role.id"
                            >
                                {{ role.name }}
                            </option>
                        </vee-field>
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="role_id"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-auto">
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
                            {{ id == 0 ? "Add" : "Update" }}
                        </button>
                    </div>
                </div>
            </div>
        </vee-form>
    </div>
</template>

<script>
import { create, update } from "@/api/user";
import { fetchRoles } from "@/api/role";
export default {
    name: "Specialities",
    props: {
        userData: {
            type: Object,
            default: {},
        },
        id: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            submittingForm: false,
            showMsg: false,
            msg: null,
            msgType: "success",
            name: this.id > 0 ? this.userData.name : null,
            email: this.id > 0 ? this.userData.email : null,
            role_id: this.id > 0 ? this.userData.role_id : null,
            schema: {
                name: "required|min:3|max:50|regex:^([^0-9]*)$",
                email: "required|email",
                role_id: "required",
            },
            roles: [],
            msgClass: "text-success",
        };
    },
    mounted() {
        this.getRoles();
    },
    methods: {
        getRoles() {
            fetchRoles().then((response) => {
                this.roles = response.data;
            });
        },
        submit(values, { resetForm }) {
            this.submittingForm = true;
            const payload = {
                name: this.name,
                email: this.email,
                role_id: this.role_id,
            };
            if (this.id > 0) {
                update(payload, this.id)
                    .then((response) => {
                        this.submittingForm = false;
                        this.handleResponse(response);
                        if (response.success == true) {
                            resetForm();
                            this.$emit("changePage", 0);
                        }
                    })
                    .catch((error) => {
                        this.submittingForm = false;
                        this.handleCatch(error);
                    });
            } else {
                create(payload)
                    .then((response) => {
                        this.submittingForm = false;
                        this.handleResponse(response);
                        if (response.success == true) {
                            resetForm();
                            this.$emit("changePage", 0);
                        }
                    })
                    .catch((error) => {
                        this.submittingForm = false;
                        this.handleCatch(error);
                    });
            }
        },
    },
};
</script>
