<template>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="addTeam"
            enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">Basic Details</h4>
                </div>
                <div class="col-md-12">
                    <div class="form-field">
                        <label for="">Name<sup>*</sup></label>
                        <vee-field
                            name="name"
                            type="text"
                            v-on:input="onChangeOfTitle()"
                            v-model="formData.name"
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
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Designation<sup>*</sup></label>
                        <vee-field
                            name="designation"
                            type="text"
                            v-model="formData.designation"
                            class="form-control"
                            placeholder="Enter Designation"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="designation"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Email<sup>*</sup></label>
                        <vee-field
                            name="email"
                            type="text"
                            v-model="formData.email"
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

                <div class="col-12">
                    <div class="form-field">
                        <div class="row">
                            <div class="col">
                                <label for="">Upload Image</label>
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
                                        dragging ? 'dropZone-over' : '',
                                    ]"
                                    @dragenter="dragging = true"
                                    @dragleave="dragging = false"
                                >
                                    <div class="dropZone-info" @drag="onChange">
                                        <i class="icon-upload"></i>
                                        <p class="dropZone-title">
                                            Drag & drop or
                                            <strong>browse</strong> your file.
                                        </p>
                                        <small v-if="imageName">
                                            {{ imageName }}
                                            <!-- <button class="btn" @click.stop="removeImage()"><i class="icon-delete"></i></button> -->
                                        </small>
                                    </div>
                                    <input type="file" @change="onChange" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-field">
                        <label for="">Bio<sup>*</sup></label>
                        <ckeditor
                            :editor="editor"
                            v-model="formData.description"
                            :config="editorConfig"
                        ></ckeditor>
                        <div class="error invalid-feedback">
                            {{ descError }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">SEO Fields</h4>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Meta Title</label>
                        <vee-field
                            name="meta_title"
                            type="text"
                            v-model="formData.meta_title"
                            class="form-control"
                            placeholder="Enter Meta Title"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="meta_title"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Meta Description</label>
                        <vee-field
                            name="meta_description"
                            type="text"
                            v-model="formData.meta_description"
                            class="form-control"
                            placeholder="Enter Meta Description"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="meta_description"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Meta Keywords</label>
                        <vee-field
                            name="meta_keywords"
                            type="text"
                            v-model="formData.meta_keywords"
                            class="form-control"
                            placeholder="Enter Meta Keywords"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="meta_keywords"
                            />
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
import { create } from "@/api/Team";
export default {
    data() {
        return {
            imageName: "",
            dragging: false,
            image: null,
            formData: {
                name: null,
                image: null,
                description: "",
                email: null,
                designation: null,
                meta_title: null,
                meta_description: null,
                meta_keywords: null,
            },
            schema: {
                name: "required|min:1|max:300|regex:^([^0-9]*)$",
                email: "required|email",
                designation: "required",
            },
            descError: null,
        };
    },
    components: {},
    mounted() {},
    methods: {
        addTeam(values, { resetForm }) {
            this.descError = null;
            if (this.formData.description == "") {
                this.descError = "This field is required.";
            } else if (this.formData.description.length < 50) {
                this.descError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("image", this.file);
                data.append("name", this.formData.name);
                data.append("email", this.formData.email);
                data.append("designation", this.formData.designation);
                data.append("description", this.formData.description);
                data.append("meta_title", this.formData.meta_title);
                data.append("meta_description", this.formData.meta_description);
                data.append("meta_keywords", this.formData.meta_keywords);
                create(data)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
                            this.file = "";
                            resetForm();
                            this.$router.push({
                                path: `/team-members`,
                            });
                        }
                    })
                    .catch((error) => {
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
        onChange(e) {
            var files = e.target.files || e.dataTransfer.files;

            if (!files.length) {
                this.dragging = false;
                return;
            }

            this.createFile(files[0]);
        },
        createFile(file) {
            if (
                !file.type.match("jpg.*") &&
                !file.type.match("jpeg.*") &&
                !file.type.match("png.*")
            ) {
                this.handleResponse({
                    message: "Format accepted: jpg, jpeg, png",
                    success: false,
                });
                this.dragging = false;
                return;
            }

            if (file.size > 5000000) {
                this.handleResponse({
                    message: "please check file size no over 5 MB.",
                    success: false,
                });
                this.dragging = false;
                return;
            }
            this.imageName = file.name;
            this.file = file;
            this.dragging = false;
        },
        removeFile() {
            this.file = "";
        },
        onChangeOfTitle() {
            this.formData.meta_title = this.formData.name;
            this.formData.meta_description = this.formData.name;
            this.formData.meta_keywords = this.formData.name;
        },
    },
    computed: {
        extension() {
            return this.file ? this.file.name.split(".").pop() : "";
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
