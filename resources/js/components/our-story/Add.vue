<template>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="submitData"
            enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">Basic Details</h4>
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
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">First Counter Label<sup>*</sup></label>
                        <vee-field
                            name="first_counter_label"
                            type="text"
                            v-model="formData.first_counter_label"
                            class="form-control"
                            placeholder="Enter First Counter Label"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="first_counter_label"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">First Counter Value<sup>*</sup></label>
                        <vee-field
                            name="first_counter_value"
                            type="text"
                            v-model="formData.first_counter_value"
                            class="form-control"
                            placeholder="Enter First Counter Value"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="first_counter_value"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Second Counter Label<sup>*</sup></label>
                        <vee-field
                            name="second_counter_label"
                            type="text"
                            v-model="formData.second_counter_label"
                            class="form-control"
                            placeholder="Enter Second Counter Label"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="second_counter_label"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Second Counter Value<sup>*</sup></label>
                        <vee-field
                            name="second_counter_value"
                            type="text"
                            v-model="formData.second_counter_value"
                            class="form-control"
                            placeholder="Enter Second Counter Value"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="second_counter_value"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Third Counter Label<sup>*</sup></label>
                        <vee-field
                            name="third_counter_label"
                            type="text"
                            v-model="formData.third_counter_label"
                            class="form-control"
                            placeholder="Enter Third Counter Label"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="third_counter_label"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Third Counter Value<sup>*</sup></label>
                        <vee-field
                            name="third_counter_value"
                            type="text"
                            v-model="formData.third_counter_value"
                            class="form-control"
                            placeholder="Enter Third Counter Value"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="third_counter_value"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-field">
                        <div class="row">
                            <div class="col">
                                <label for="">Upload Primary Image</label>
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-field-info">
                                    <i class="icon-bulb text-secondary"></i>
                                    Format accepted: jpg, jpeg, png
                                </div>
                            </div>
                        </div>
                        <div class="uploader position-relative">
                            <div class="uploader-info">
                                <div
                                    :class="[
                                        'dropZone',
                                        primaryDragging ? 'dropZone-over' : '',
                                    ]"
                                    @dragenter="primaryDragging = true"
                                    @dragleave="primaryDragging = false"
                                >
                                    <div
                                        class="dropZone-info"
                                        @drag="onPrimaryImgChange"
                                    >
                                        <i class="icon-upload"></i>
                                        <p class="dropZone-title">
                                            Drag & drop or
                                            <strong>browse</strong> your file.
                                        </p>
                                        <small v-if="primaryImgName">
                                            {{ primaryImgName }}
                                        </small>
                                    </div>
                                    <input
                                        type="file"
                                        @change="onPrimaryImgChange"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-field mb-2">
                        <div class="row">
                            <div class="col">
                                <label for=""
                                    >Upload Secondary Image<sup>*</sup></label
                                >
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-field-info">
                                    <i class="icon-bulb text-secondary"></i>
                                    Format accepted: jpg, jpeg, png
                                </div>
                            </div>
                        </div>
                        <div class="uploader position-relative">
                            <div class="uploader-info">
                                <div
                                    :class="[
                                        'dropZone',
                                        secondaryDragging
                                            ? 'dropZone-over'
                                            : '',
                                    ]"
                                    @dragenter="secondaryDragging = true"
                                    @dragleave="secondaryDragging = false"
                                >
                                    <div
                                        class="dropZone-info"
                                        @drag="onSecondaryImgChange"
                                    >
                                        <i class="icon-upload"></i>
                                        <p class="dropZone-title">
                                            Drag & drop or
                                            <strong>browse</strong> your file.
                                        </p>
                                        <small v-if="secondaryImgName">
                                            {{ secondaryImgName }}
                                        </small>
                                    </div>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        @change="onSecondaryImgChange"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="error invalid-feedback">
                        {{ imgError }}
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
<style src="@vueform/multiselect/themes/default.css"></style>
<script>
import { create, fetch } from "@/api/page";
export default {
    data() {
        return {
            primaryImgName: null,
            primaryImgFile: "",
            primaryDragging: false,
            secondaryImgName: null,
            secondaryImgFile: "",
            secondaryDragging: false,
            formData: {
                sub_header: null,
                content: "",
                primary_image: null,
                secondary_image: null,
                first_counter_label: null,
                first_counter_value: null,
                second_counter_label: null,
                second_counter_value: null,
                third_counter_label: null,
                third_counter_value: null,
            },
            schema: {
                sub_header: "required|min:3|max:300",
                third_counter_label: "required",
                third_counter_label: "required",
                second_counter_value: "required",
                second_counter_label: "required",
                first_counter_label: "required",
                first_counter_value: "required",
            },
            contentError: null,
            primaryImgError: null,
            secondaryImgError: null,
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            fetch("our-story").then((response) => {
                if (response.success === true) {
                    if (response.data != null) {
                        this.formData = response.data;
                    }
                }
            });
        },
        submitData(values, { resetForm }) {
            this.contentError = null;
            this.primaryImgError = null;
            this.secondaryImgError = null;
            if (this.formData.content == "") {
                this.contentError = "This field is required.";
            } else if (this.formData.content.length < 50) {
                this.contentError = "Minimum 50 characters are allowed.";
            } else if (this.primaryImgFile == "") {
                this.primaryImgError = "Please upload primary image.";
            } else if (this.secondaryImgFile == "") {
                this.secondaryImgError = "Please upload secondary image.";
            } else {
                let data = new FormData();
                data.append("primary_image", this.primaryImgFile);
                data.append("secondary_image", this.secondaryImgFile);
                data.append("sub_header", this.formData.sub_header);
                data.append("content", this.formData.content);
                data.append("page_name", "our-story");
                data.append(
                    "first_counter_label",
                    this.formData.first_counter_label
                );
                data.append(
                    "first_counter_value",
                    this.formData.first_counter_value
                );
                data.append(
                    "second_counter_label",
                    this.formData.second_counter_label
                );
                data.append(
                    "second_counter_value",
                    this.formData.second_counter_value
                );
                data.append(
                    "third_counter_label",
                    this.formData.third_counter_label
                );
                data.append(
                    "third_counter_value",
                    this.formData.third_counter_value
                );

                create(data)
                    .then((response) => {
                        this.handleResponse(response);
                    })
                    .catch((error) => {
                        this.handleCatch(error);
                    });
            }
        },
        onPrimaryImgChange(e) {
            this.imgError = null;
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                this.primaryDragging = false;
                return;
            }
            this.createPrimaryImg(files[0]);
        },
        createPrimaryImg(file) {
            if (
                !file.type.match("jpg.*") &&
                !file.type.match("jpeg.*") &&
                !file.type.match("png.*")
            ) {
                this.handleResponse({
                    message: "Format accepted: jpg, jpeg, png",
                    success: false,
                });
                this.primaryDragging = false;
                return;
            }

            if (file.size > 5000000) {
                this.handleResponse({
                    message: "please check file size no over 5 MB.",
                    success: false,
                });
                this.primaryDragging = false;
                return;
            }
            this.primaryImgName = file.name;
            this.primaryImgFile = file;
            this.primaryDragging = false;
        },
        onSecondaryImgChange(e) {
            this.imgError = null;
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                this.secondaryDragging = false;
                return;
            }
            this.createSecondaryImg(files[0]);
        },
        createSecondaryImg(file) {
            if (
                !file.type.match("jpg.*") &&
                !file.type.match("jpeg.*") &&
                !file.type.match("png.*")
            ) {
                this.handleResponse({
                    message: "Format accepted: jpg, jpeg, png",
                    success: false,
                });
                this.secondaryDragging = false;
                return;
            }

            if (file.size > 5000000) {
                this.handleResponse({
                    message: "please check file size no over 5 MB.",
                    success: false,
                });
                this.secondaryDragging = false;
                return;
            }
            this.secondaryImgName = file.name;
            this.secondaryImgFile = file;
            this.secondaryDragging = false;
        },
        onFileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                this.dragging = false;
                return;
            }

            this.createFile(files[0]);
        },
    },
    computed: {},
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
.dropZone-title {
    color: #787878;
}

.dropZone input {
    position: absolute;
    cursor: pointer;
    top: 0px;
    right: 0;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
}

.dropZone-upload-limit-info {
    display: flex;
    justify-content: flex-start;
    flex-direction: column;
}

.dropZone-uploaded {
    width: 80%;
    height: 200px;
    position: relative;
}

.dropZone-uploaded-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: #a8a8a8;
    position: absolute;
    top: 50%;
    width: 100%;
    transform: translate(0, -50%);
    text-align: center;
}
</style>
