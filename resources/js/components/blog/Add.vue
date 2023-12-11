<template>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="addBlog"
            enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">Basic Details</h4>
                </div>
                <div class="col-md-12">
                    <div class="form-field">
                        <label for="">Title<sup>*</sup></label>
                        <vee-field
                            name="title"
                            type="text"
                            v-on:input="onChangeOfTitle()"
                            v-model="formData.title"
                            class="form-control"
                            placeholder="Enter Title"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="title"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Author's Name<sup>*</sup></label>
                        <vee-field
                            name="author_name"
                            type="text"
                            v-model="formData.author_name"
                            class="form-control"
                            placeholder="Enter Author's Name"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="author_name"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Categories<sup>*</sup></label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="categories"
                            type="text"
                            v-model="formData.categories"
                        >
                            <Multiselect
                                v-model="formData.categories"
                                mode="tags"
                                v-bind="field"
                                :close-on-select="false"
                                :searchable="true"
                                :create-option="false"
                                valueProp="id"
                                label="name"
                                :required="true"
                                :options="categories"
                                placeholder="Select Categories"
                            />
                            <div
                                class="error invalid-feedback"
                                v-if="errors[0]"
                            >
                                {{ errors[0] }}
                            </div>
                        </vee-field>
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
                        <label for="">Description<sup>*</sup></label>
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
import { create } from "@/api/blog";
export default {
    data() {
        return {
            imageName: "",
            dragging: false,
            image: null,
            formData: {
                title: null,
                author_name: null,
                image: null,
                description: "",
                categories: [],
                meta_title: null,
                meta_description: null,
                meta_keywords: null,
            },
            schema: {
                title: "required|min:3|max:300",
                author_name: "required|min:3|max:50|regex:^([^0-9]*)$",
                categories: "required",
            },
            descError: null,
        };
    },
    mounted() {
        this.getCategories("blog");
    },
    methods: {
        addBlog(values, { resetForm }) {
            this.descError = null;
            if (this.formData.description == "") {
                this.descError = "This field is required.";
            } else if (this.formData.description.length < 50) {
                this.descError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("image", this.file);
                data.append("title", this.formData.title);
                data.append("author_name", this.formData.author_name);
                data.append("categories", this.formData.categories);
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
                                path: `/blogs/list`,
                            });
                        }
                    })
                    .catch((error) => {
                        this.handleCatch(error);
                    });
            }
        },
        onChangeOfTitle() {
            this.formData.meta_title = this.formData.title;
            this.formData.meta_description = this.formData.title;
            this.formData.meta_keywords = this.formData.title;
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
