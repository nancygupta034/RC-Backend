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
                <div class="col-12">
                    <div class="form-field">
                        <label for="">Terms & Conditions<sup>*</sup></label>
                        <ckeditor
                            :editor="editor"
                            v-model="formData.terms_conditions"
                            :config="editorConfig"
                        ></ckeditor>
                        <div class="error invalid-feedback">
                            {{ tcContentError }}
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
<style src="@vueform/multiselect/themes/default.css"></style>
<script>
import { create, fetch } from "@/api/page";
export default {
    data() {
        return {
            formData: {
                terms_conditions: "",
            },
            tcContentError: null,
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            fetch("psych-testing").then((response) => {
                if (response.success === true) {
                    if (response.data != null) {
                        this.formData = response.data;
                    }
                }
            });
        },
        submitData(values, { resetForm }) {
            this.tcContentError = null;
            if (this.formData.terms_conditions == "") {
                this.tcContentError = "This field is required.";
            } else if (this.formData.terms_conditions.length < 50) {
                this.tcContentError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("page_name", "psych-testing");
                data.append("terms_conditions", this.formData.terms_conditions);
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
<style scoped>
.button {
    position: relative;
}

.button.loading:after {
    content: "";
    position: absolute;
    border-radius: 100%;
    right: 6px;
    top: 50%;
    width: 0px;
    height: 0px;
    margin-top: -2px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    border-left-color: #fff;
    border-top-color: #fff;
    animation: spin 0.6s infinite linear, grow 0.3s forwards ease-out;
}
.btn .icon-delete {
    font-size: 18px;
    padding: 0px 0px !important;
    color: red;
}
@keyframes spin {
    to {
        transform: rotate(359deg);
    }
}
@keyframes grow {
    to {
        width: 14px;
        height: 14px;
        margin-top: -8px;
        right: 13px;
    }
}
</style>
