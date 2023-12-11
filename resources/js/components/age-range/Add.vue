<template>
    <div class="form-box mb-3">
        <vee-form :validation-schema="schema" @submit="submit" ref="ageForm">
            <div class="row">
                <div class="col col-xl-3">
                    <div class="form-field">
                        <vee-field
                            type="number"
                            v-model="min_age"
                            class="form-control"
                            placeholder="Enter Minimum Age"
                            name="min_age"
                            min="0"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="min_age"
                            />
                        </div>
                    </div>
                </div>
                <div class="col col-xl-3">
                    <div class="form-field">
                        <vee-field
                            name="max_age"
                            type="number"
                            v-model="max_age"
                            class="form-control"
                            placeholder="Enter Maximum Age"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="max_age"
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
import { create, update } from "@/api/ageRange";
export default {
    name: "AgeRangeAdd",
    props: {
        minAge: {
            type: String,
            default: null,
        },
        maxAge: {
            type: String,
            default: null,
        },
        id: {
            type: Number,
            default: 0,
        },
    },
    data() {
        return {
            min_age: this.minAge,
            max_age: this.maxAge,
            schema: {
                min_age: "required|numeric|min_value:0|max_value:200",
                max_age: "required|numeric|min_value:1|max_value:200",
            },
        };
    },
    methods: {
        submit(values, { resetForm }) {
            if (this.id > 0) {
                update(
                    { min_age: this.min_age, max_age: this.max_age },
                    this.id
                )
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
                create({ min_age: this.min_age, max_age: this.max_age })
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
