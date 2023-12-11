<template>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="updateService"
            enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">Basic Details</h4>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Title<sup>*</sup></label>
                        <vee-field
                            name="title"
                            type="text"
                            v-on:input="onChangeOfTitle()"
                            v-model="serviceData.title"
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
                        <label for="">Sub Title<sup>*</sup></label>
                        <vee-field
                            name="sub_title"
                            type="text"
                            v-model="serviceData.sub_title"
                            class="form-control"
                            placeholder="Enter Sub Title"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="sub_title"
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
                        <label for="">Description<sup>*</sup></label>
                        <ckeditor
                            :editor="editor"
                            v-model="serviceData.description"
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
                            v-model="serviceData.meta_title"
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
                            v-model="serviceData.meta_description"
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
                            v-model="serviceData.meta_keywords"
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
import { update } from "@/api/service";
import { fetch } from "@/api/service";
export default {
    data() {
        return {
            imageName: "",
            dragging: false,
            image: null,
            serviceData: {
                title: null,
                image: null,
                sub_title: null,
                description: "",
                meta_title: null,
                meta_description: null,
                meta_keywords: null,
            },
            schema: {
                title: "required|alpha_spaces|min:1|max:50|regex:^([^0-9]*)$",
                sub_title: "required|min:3|max:200",
            },
            descError: null,
        };
    },
    async mounted() {
        await this.getService();
    },
    methods: {
        async getService() {
            const id = `${this.$route.params.serviceId}`;
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.serviceData = response.data;
                    this.imageName = this.serviceData.image
                        ? this.serviceData.image.slice(
                              this.serviceData.image.indexOf("uploads/") + 8
                          )
                        : null;
                }
            });
        },
        updateService() {
            this.descError = null;
            if (this.serviceData.description == "") {
                this.descError = "This field is required.";
            } else if (this.serviceData.description.length < 50) {
                this.descError = "Minimum 50 characters should be there.";
            } else {
                let data = new FormData();
                data.append("image", this.file);
                data.append("title", this.serviceData.title);
                data.append("sub_title", this.serviceData.sub_title);
                data.append("description", this.serviceData.description);
                data.append("meta_title", this.serviceData.meta_title);
                data.append(
                    "meta_description",
                    this.serviceData.meta_description
                );
                data.append("meta_keywords", this.serviceData.meta_keywords);
                update(data, this.serviceData.id)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
                            this.$router.push({
                                path: `/services/list`,
                            });
                        }
                    })
                    .catch((error) => {
                        this.handleCatch(error);
                    });
            }
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
        onChangeOfTitle() {
            this.serviceData.meta_title = this.serviceData.title;
            this.serviceData.meta_description = this.serviceData.title;
            this.serviceData.meta_keywords = this.serviceData.title;
        },
    },
};
</script>
<style scoped>
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
