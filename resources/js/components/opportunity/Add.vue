<template>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="submitData"
            enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">Details</h4>
                </div>
                <div class="col-md-12">
                    <div class="form-field">
                        <label for="">Sub Heading<sup>*</sup></label>
                        <vee-field
                            name="sub_header"
                            type="text"
                            v-model="formData.sub_header"
                            class="form-control"
                            placeholder="Enter Sub Heading"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="sub_header"
                            />
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-field">
                        <label for="">Content<sup>*</sup></label>
                        <ckeditor
                            :editor="editor"
                            v-model="formData.content"
                            :config="editorConfig"
                        ></ckeditor>
                        <div class="error invalid-feedback">
                            {{ contentError }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="submit-wrap pt-3">
                <button type="submit" class="btn btn-secondary">SUBMIT</button>
            </div>
        </vee-form>
    </div>
</template>
<script>
import { create, fetch } from "@/api/page";
export default {
    data() {
        return {
            formData: {
                sub_header: null,
                content: "",
            },
            schema: {
                sub_header: "required|min:3|max:300",
            },
            contentError: null,
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            fetch("opportunity").then((response) => {
                if (response.success === true) {
                    if (response.data != null) {
                        this.formData = response.data;
                    }
                }
            });
        },
        submitData(values, { resetForm }) {
            this.contentError = null;
            if (this.formData.content == "") {
                this.contentError = "This field is required.";
            } else if (this.formData.content.length < 50) {
                this.contentError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("content", this.formData.content);
                data.append("sub_header", this.formData.sub_header);
                data.append("page_name", "opportunity");
                create(data)
                    .then((response) => {
                        this.handleResponse(response);
                    })
                    .catch((error) => {
                        this.handleCatch(error);
                    });
            }
        },
    },
};
</script>

