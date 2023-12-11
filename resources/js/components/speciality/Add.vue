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
                            :validateOnModelUpdate="false"
                            :validateOnBlur="false"
                            :validateOnChange="false"
                            :validateOnInput="false"
                            :validateOnMount="false"
                            class="form-control"
                            placeholder="Enter Speciality Name"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="name"
                            />
                        </div>
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
import { createSpeciality, updateSpeciality } from "@/api/speciality";
export default {
    name: "Specialities",
    props: {
        specialityName: {
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
            name: this.specialityName != "" ? this.specialityName : null,
            schema: {
                name: "required|min:1|max:50|regex:^([^0-9]*)$",
            },
        };
    },
    methods: {
        submit(values, { resetForm }) {
            if (this.id > 0) {
                updateSpeciality({ name: this.name }, this.id)
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
                createSpeciality({ name: this.name })
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
        },
    },
};
</script>
