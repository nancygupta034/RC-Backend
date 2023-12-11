<template>
    <div class="form-box mb-3">
        <vee-form :validation-schema="schema" @submit="submit">
            <div class="row">
                <div class="col col-xl-6">
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
                                class="invalid-feedback"
                                name="name"
                            />
                        </div>
                        <p v-if="showMsg" :class="msgClass">{{ msg }}</p>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="submit-wrap">
                        <button
                            type="submit"
                            class="btn btn-primary-light btn-medium min-small"
                        >
                            {{ id == 0 ? "Add" : "Update" }}
                        </button>
                    </div>
                </div>
            </div>
        </vee-form>
    </div>
</template>

<script>
import { create, update } from "@/api/servicesProvided";
export default {
    name: "ServicesProvidedAdd",
    props: {
        servicesProvidedName: {
            type: String,
            default: "",
        },
        id: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            showMsg: false,
            msg: null,
            msgType: "success",
            name:
                this.servicesProvidedName != ""
                    ? this.servicesProvidedName
                    : null,
            schema: {
                name: "required|min:3|max:50|regex:^([^0-9]*)$",
            },
            msgClass: "text-success",
        };
    },
    methods: {
        submit(values, { resetForm }) {
            if (this.id > 0) {
                update({ name: this.name }, this.id)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
                            resetForm();
                            this.$emit("changePage", 0);
                        }
                    })
                    .catch((error) => {
                        this.handleCatch(error);
                    });
            } else {
                create({ name: this.name })
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
                            resetForm();
                            this.$emit("changePage", 0);
                        }
                    })
                    .catch((error) => {
                        this.handleCatch(error);
                    });
            }
            resetForm();
        },
    },
};
</script>
