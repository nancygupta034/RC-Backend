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
                        <label for="">Categories<sup>*</sup></label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-select"
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
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Type</label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-select"
                            name="type"
                            type="text"
                            v-model="formData.type"
                        >
                            <Multiselect
                                v-model="formData.type"
                                mode="single"
                                v-bind="field"
                                :close-on-select="true"
                                :searchable="true"
                                @change="onTypeChange"
                                :create-option="false"
                                valueProp="value"
                                label="text"
                                :required="true"
                                :options="types"
                                placeholder="Select Type"
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

                <div class="col-12" v-if="formData.type == 'image'">
                    <div class="form-field mb-2">
                        <div class="row">
                            <div class="col">
                                <label for="">Upload Image<sup>*</sup></label>
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
                                    </div>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        multiple
                                        @change="onChange"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="error invalid-feedback">
                        {{ imgError }}
                    </div>

                    <div class="thumbWrapper">
                        <ul class="uploaded-images" v-if="images">
                            <li v-for="(image, index) in images">
                                <div class="thumbBox">
                                    <a
                                        href=""
                                        class="delete"
                                        @click.prevent="removeImage(index)"
                                        ><i class="bi bi-trash-fill"></i></a
                                    ><img :src="image" alt="" />
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12" v-if="formData.type == 'file'">
                    <div class="form-field">
                        <div class="row">
                            <div class="col">
                                <label for="">Upload File<sup>*</sup></label>
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-field-info">
                                    <i class="icon-bulb text-secondary"></i>
                                    Format accepted: doc, docx, pdf, xlsx
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
                                    <div
                                        class="dropZone-info"
                                        @drag="onFileChange"
                                    >
                                        <i class="icon-upload"></i>
                                        <p class="dropZone-title">
                                            Drag & drop or
                                            <strong>browse</strong> your file.
                                        </p>
                                        <small v-if="fileName">
                                            {{ fileName }}
                                        </small>
                                    </div>
                                    <input type="file" @change="onFileChange" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12" v-if="formData.type == 'video'">
                    <div class="form-field">
                        <div class="row">
                            <div class="col">
                                <label for="">Upload Video<sup>*</sup></label>
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-field-info">
                                    <i class="icon-bulb text-secondary"></i>
                                    Format accepted: mp4, mov
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
                                    <div
                                        class="dropZone-info"
                                        @drag="onVideoChange"
                                    >
                                        <i class="icon-upload"></i>
                                        <p class="dropZone-title">
                                            Drag & drop or
                                            <strong>browse</strong> your file.
                                        </p>
                                        <small v-if="videoName">
                                            {{ videoName }}
                                        </small>
                                    </div>
                                    <input
                                        type="file"
                                        @change="onVideoChange"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="video"
                            />
                        </div>
                    </div>
                    <div class="error invalid-feedback">
                        {{ videoError }}
                    </div>
                </div>
                <div class="col-12" v-if="formData.type == 'youtube-link'">
                    <label for="">Youtube Link<sup>*</sup></label>
                    <vee-field
                        name="youtubeLink"
                        type="text"
                        v-model="formData.youtubeLink"
                        class="form-control"
                        placeholder="https://www.youtube.com/watch?v=9y3N0TYr_Yc"
                    />
                    <div class="error invalid-feedback">
                        <ErrorMessage
                            class="invalid-feedback"
                            name="youtubeLink"
                        />
                    </div>
                </div>
                <div class="col-12" v-if="formData.type == 'vimeo-link'">
                    <label for="">Vimeo Link<sup>*</sup></label>
                    <vee-field
                        name="vimeoLink"
                        type="text"
                        v-model="formData.vimeoLink"
                        class="form-control"
                        placeholder="https://vimeo.com/22484953"
                    />
                    <div class="error invalid-feedback">
                        <ErrorMessage
                            class="invalid-feedback"
                            name="vimeoLink"
                        />
                    </div>
                </div>
                <div class="col-12" v-if="formData.type == 'external-link'">
                    <label for="">External Link<sup>*</sup></label>
                    <vee-field
                        name="externalLink"
                        type="text"
                        v-model="formData.externalLink"
                        class="form-control"
                        placeholder="http://"
                    />
                    <div class="error invalid-feedback">
                        <ErrorMessage
                            class="invalid-feedback"
                            name="externalLink"
                        />
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
import { create } from "@/api/mentalHealth";
export default {
    data() {
        return {
            images: [],
            uploadedImages: [],
            fileName: "",
            videoName: "",
            dragging: false,
            formData: {
                title: null,
                description: "",
                type: "",
                categories: [],
                meta_title: null,
                meta_description: null,
                meta_keywords: null,
                images: [],
                files: [],
                video: "",
                externalLink: "",
                youtubeLink: "",
                vimeoLink: "",
            },
            schema: {
                title: "required|min:3|max:300",
                categories: "required",
                type: "required",
            },
            types: [
                {
                    text: "Images",
                    value: "image",
                },
                {
                    text: "Video",
                    value: "video",
                },
                {
                    text: "Youtube Link",
                    value: "youtube-link",
                },
                {
                    text: "External Link",
                    value: "external-link",
                },
                {
                    text: "File",
                    value: "file",
                },
            ],
            descError: null,
            imgError: null,
            videoError: null,
        };
    },
    mounted() {
        this.getCategories("mental-health");
    },
    methods: {
        submitData(values, { resetForm }) {
            this.descError = null;
            this.imgError = null;
            if (this.formData.description == "") {
                this.descError = "This field is required.";
            } else if (this.formData.description.length < 50) {
                this.descError = "Minimum 50 characters are allowed.";
            } else if (
                this.formData.type == "image" &&
                !this.uploadedImages.length
            ) {
                this.imgError = "Please upload at least 1 image.";
            } else if (this.formData.type == "video" && this.file == "") {
                this.videoError = "Please upload video.";
            } else {
                let data = new FormData();
                data.append("file", this.file);
                data.append("video", this.file);
                data.append("title", this.formData.title);
                data.append("type", this.formData.type);
                data.append("categories", this.formData.categories);
                data.append("description", this.formData.description);
                data.append("meta_title", this.formData.meta_title);
                data.append("meta_description", this.formData.meta_description);
                data.append("meta_keywords", this.formData.meta_keywords);
                data.append("youtube_link", this.formData.youtubeLink);
                data.append("external_link", this.formData.externalLink);
                for (let i = 0; i < this.uploadedImages.length; i++) {
                    data.append("images[]", this.uploadedImages[i]);
                }
                create(data)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
                            this.file = "";
                            resetForm();
                            this.$router.push({
                                path: `/mental-health/list`,
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
        onTypeChange() {
            this.videoError = null;
            this.imgError = null;
            this.schema.youtubeLink = "";
            this.schema.vimeoLink = "";
            this.schema.externalLink = "";
            this.videoName = "";
            this.file = "";

            if (this.formData.type == "youtube-link") {
                this.schema.youtubeLink =
                    "required|regex:^(https?\:\/\/)?((www\.)?youtube\.com)\/.+$|url";
            } else if (this.formData.type == "vimeo-link") {
                this.schema.vimeoLink = "required";
            } else if (this.formData.type == "external-link") {
                this.schema.externalLink = "required|url";
            }
        },
        onChange(e) {
            this.imgError = null;
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                this.dragging = false;
                return;
            }
            this.createImage(files);
        },
        removeImage(index) {
            this.images.splice(index, 1);
        },
        createImage(files) {
            var vm = this;
            for (var index = 0; index < files.length; index++) {
                let file = files[index];
                if (
                    !file.type.match("jpg.*") &&
                    !file.type.match("jpeg.*") &&
                    !file.type.match("png.*")
                ) {
                    this.handleResponse({
                        message: "Format accepted: jpg, jpeg, png",
                        success: false,
                    });
                    vm.images = [];
                    this.dragging = false;
                    return;
                }
                if (file.size > 5000000) {
                    this.handleResponse({
                        message: "Maximum file size whould be 5 MB.",
                        success: false,
                    });
                    vm.images = [];
                    this.dragging = false;
                    return;
                }
                var reader = new FileReader();
                reader.onload = function (event) {
                    const imageUrl = event.target.result;
                    vm.images.push(imageUrl);
                };
                reader.readAsDataURL(files[index]);
                this.uploadedImages.push(files[index]);
            }
        },
        createVideo(file) {
            if (!file.type.match("mp4.*") && !file.type.match("mov.*")) {
                this.handleResponse({
                    message: "Format accepted: mp4, mov",
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
            this.videoName = file.name;
            this.file = file;
            this.dragging = false;
        },
        createFile(file) {
            if (
                !file.type.match("application/msword.*") &&
                !file.type.match("application/pdf.*") &&
                !file.type.match("application/vnd.ms-excel.*")
            ) {
                this.handleResponse({
                    message: "Format accepted: doc, docx, pdf, xlsx",
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
            this.fileName = file.name;
            this.file = file;
            this.dragging = false;
        },
        removeFile() {
            this.file = "";
        },
        onVideoChange(e) {
            this.videoError = null;
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                this.dragging = false;
                return;
            }

            this.createVideo(files[0]);
        },
        onFileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                this.dragging = false;
                return;
            }

            this.createFile(files[0]);
        },
        getYouTubeVideoId(videoUrl) {
            // Regular expression to match YouTube video IDs
            const regex =
                /(?:youtube\.com\/(?:[^/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?/ ]{11})/;

            // Try to match the regex with the video URL
            const match = videoUrl.match(regex);

            if (match && match[1]) {
                // match[1] contains the YouTube video ID
                return match[1];
            } else {
                // Invalid or unrecognized YouTube video URL
                return null;
            }
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
