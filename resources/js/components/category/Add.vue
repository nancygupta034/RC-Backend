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
                            placeholder="Enter Name"
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
import { createBlogCategory, updateCategories } from "@/api/category";
export default {
    name: "AddBlogCategories",
    props: {
        blogCategoryName: {
            type: String,
            default: "",
        },
        type: {
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
            name: this.blogCategoryName != "" ? this.blogCategoryName : null,
            schema: {
                name: "required|min:3|max:50|regex:^([^0-9]*)$",
            },
        };
    },
    methods: {
        submit(values, { resetForm }) {
            if (this.id > 0) {
                updateCategories({ name: this.name }, this.id)
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
                createBlogCategory({ name: this.name, type: this.type })
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
