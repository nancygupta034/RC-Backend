<template>
    <div class="overlay" v-if="displayOverlay">
        <div class="overlay__inner">
            <div class="overlay__content">
                <span class="spinner"></span>
            </div>
        </div>
    </div>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="updateMentalHealth"
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
                            v-model="mentalHealthData.title"
                            v-on:input="onChangeOfTitle()"
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
                            class="form-control"
                            name="categories"
                            type="text"
                            v-model="mentalHealthData.categoryIds"
                        >
                            <Multiselect
                                v-model="mentalHealthData.categoryIds"
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
                            v-model="mentalHealthData.type"
                        >
                            <Multiselect
                                v-model="mentalHealthData.type"
                                @change="onTypeChange"
                                mode="single"
                                v-bind="field"
                                :close-on-select="true"
                                :searchable="true"
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
                <div class="col-12" v-if="mentalHealthData.type == 'image'">
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
                <div class="col-12" v-if="mentalHealthData.type == 'file'">
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
                                    <input
                                        type="file"
                                        @change="onFileChange"
                                        accept="application/pdf,application/vnd.ms-excel,.doc,.docx"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12" v-if="mentalHealthData.type == 'video'">
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
                                        accept="video/*"
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
                <div
                    class="col-12"
                    v-if="mentalHealthData.type == 'youtube-link'"
                >
                    <label for="">Youtube Link<sup>*</sup></label>
                    <vee-field
                        name="youtubeLink"
                        type="text"
                        v-model="mentalHealthData.youtubeLink"
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
                <div
                    class="col-12"
                    v-if="mentalHealthData.type == 'vimeo-link'"
                >
                    <label for="">Vimeo Link<sup>*</sup></label>
                    <vee-field
                        name="vimeoLink"
                        type="text"
                        v-model="mentalHealthData.vimeoLink"
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
                <div
                    class="col-12"
                    v-if="mentalHealthData.type == 'external-link'"
                >
                    <label for="">External Link<sup>*</sup></label>
                    <vee-field
                        name="externalLink"
                        type="text"
                        v-model="mentalHealthData.externalLink"
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
                            v-model="mentalHealthData.description"
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
                            v-model="mentalHealthData.meta_title"
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
                            v-model="mentalHealthData.meta_description"
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
                            v-model="mentalHealthData.meta_keywords"
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
import { fetch, update } from "@/api/mentalHealth";
export default {
    data() {
        return {
            displayOverlay: true,
            images: [],
            uploadedImages: [],
            fileName: "",
            videoName: "",
            dragging: false,
            image: null,
            mentalHealthData: {
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
            tempmentalHealthData: {},
            schema: {
                title: "required|min:3|max:300",
                categories: "required",
                type: "required",
            },
            descError: null,
            imgError: null,
            videoError: null,
        };
    },
    async mounted() {
        await this.getCategories("mental-health");
        await this.getMentalHealth();
        let that = this;
        new Promise(function (resolve, reject) {
            setTimeout(() => {
                that.mentalHealthData = that.tempmentalHealthData;
                that.videoName =
                    that.mentalHealthData.type == "video" &&
                    that.mentalHealthData.video != null
                        ? that.mentalHealthData.video.slice(
                              that.mentalHealthData.video.indexOf("uploads/") +
                                  8
                          )
                        : null;

                that.file =
                    that.mentalHealthData.type == "video" &&
                    that.mentalHealthData.video != null
                        ? that.mentalHealthData.video
                        : that.mentalHealthData.type == "file"
                        ? that.mentalHealthData.file
                        : null;

                that.fileName =
                    that.mentalHealthData.type == "file" &&
                    that.mentalHealthData.file != null
                        ? that.mentalHealthData.file.slice(
                              that.mentalHealthData.file.indexOf("uploads/") + 8
                          )
                        : null;

                that.mentalHealthData.externalLink =
                    that.mentalHealthData.type == "external-link"
                        ? that.mentalHealthData.external_link
                        : null;
                that.mentalHealthData.youtubeLink =
                    that.mentalHealthData.type == "youtube-link"
                        ? that.mentalHealthData.youtube_link
                        : null;

                if (that.mentalHealthData.type == "image") {
                    for (
                        let j = 0;
                        j < that.mentalHealthData.images.length;
                        j++
                    ) {
                        that.images.push(that.mentalHealthData.images[j].image);
                    }
                }
                that.displayOverlay = false;

                resolve();
            }, 2500);
        });
    },
    methods: {
        async getMentalHealth() {
            const id = `${this.$route.params.blogId}`;
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.tempmentalHealthData = response.data;
                }
            });
        },
        onTypeChange() {
            this.videoError = null;
            this.imgError = null;
            this.schema.youtubeLink = "";
            this.schema.vimeoLink = "";
            this.schema.externalLink = "";
            this.videoName = "";
            this.file = "";

            if (this.mentalHealthData.type == "youtube-link") {
                this.schema.youtubeLink =
                    "required|regex:^(https?\:\/\/)?((www\.)?youtube\.com)\/.+$|url";
            } else if (this.mentalHealthData.type == "vimeo-link") {
                this.schema.vimeoLink = "required";
            } else if (this.mentalHealthData.type == "external-link") {
                this.schema.externalLink = "required|url";
            }
        },
        updateMentalHealth() {
            this.descError = null;
            if (this.mentalHealthData.description == "") {
                this.descError = "This field is required.";
            } else if (this.mentalHealthData.description.length < 50) {
                this.descError = "Minimum 50 characters are allowed.";
            } else if (
                this.mentalHealthData.type == "image" &&
                !this.uploadedImages.length &&
                !this.images.length
            ) {
                this.imgError = "Please upload at least 1 image.";
            } else if (
                this.mentalHealthData.type == "video" &&
                this.file == ""
            ) {
                this.videoError = "Please upload video.";
            } else {
                let data = new FormData();
                data.append("file", this.file);
                data.append("video", this.file);
                data.append("title", this.mentalHealthData.title);
                data.append("type", this.mentalHealthData.type);
                data.append("categories", this.mentalHealthData.categoryIds);
                data.append("description", this.mentalHealthData.description);
                data.append("meta_title", this.mentalHealthData.meta_title);
                data.append(
                    "meta_description",
                    this.mentalHealthData.meta_description
                );
                data.append(
                    "meta_keywords",
                    this.mentalHealthData.meta_keywords
                );
                data.append("youtube_link", this.mentalHealthData.youtubeLink);
                data.append(
                    "external_link",
                    this.mentalHealthData.externalLink
                );
                if (this.mentalHealthData.type == "image") {
                    if (this.uploadedImages.length) {
                        for (let i = 0; i < this.uploadedImages.length; i++) {
                            data.append("images[]", this.uploadedImages[i]);
                        }
                    }
                    if (this.uploadedImages.length != this.images.length) {
                        const length =
                            this.images.length - this.uploadedImages.length;
                        for (let k = 0; k < length; k++) {
                            data.append("images[]", this.images[k]);
                        }
                    }
                }

                update(data, this.mentalHealthData.id)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
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
            this.mentalHealthData.meta_title = this.mentalHealthData.title;
            this.mentalHealthData.meta_description =
                this.mentalHealthData.title;
            this.mentalHealthData.meta_keywords = this.mentalHealthData.title;
        },
        onChange(e) {
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
            // this.uploadedImages = files;
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
};
</script>
<style scoped>
.overlay {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: fixed;
    background: rgba(255, 255, 255, 0.7);
    z-index: 9999;
}

.overlay__inner {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: absolute;
}

.overlay__content {
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}

.spinner {
    width: 50px;
    height: 50px;
    display: inline-block;
    border-width: 5px;
    border-color: #0c0c0c0d;
    border-top-color: #1a1818;
    animation: spin 1s infinite linear;
    border-radius: 100%;
    border-style: solid;
}

@keyframes spin {
    100% {
        transform: rotate(360deg);
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
