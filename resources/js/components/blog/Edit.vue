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
            @submit="updateBlog"
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
                            v-model="blogData.title"
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
                        <label for="">Author's Name<sup>*</sup></label>
                        <vee-field
                            name="author_name"
                            type="text"
                            v-model="blogData.author_name"
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
                            v-model="blogData.categoryIds"
                        >
                            <Multiselect
                                v-model="blogData.categoryIds"
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
                            v-model="blogData.description"
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
                            v-model="blogData.meta_title"
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
                            v-model="blogData.meta_description"
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
                            v-model="blogData.meta_keywords"
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
import { fetchList } from "@/api/category";
import { fetch, update } from "@/api/blog";
export default {
    props: {},
    components: {},
    data() {
        return {
            displayOverlay: true,
            imageName: "",
            dragging: false,
            image: null,
            blogData: {
                title: null,
                image: null,
                sub_title: null,
                description: "",
                meta_title: null,
                meta_description: null,
                meta_keywords: null,
            },
            tempBlogData: {},
            schema: {
                title: "required|min:3|max:300",
                author_name: "required|min:3|max:50|regex:^([^0-9]*)$",
                categories: "required",
            },
            descError: null,
        };
    },
    async mounted() {
        await this.getCategories("blog");
        await this.getBlog();
        let that = this;
        new Promise(function (resolve, reject) {
            setTimeout(() => {
                that.blogData = that.tempBlogData;
                that.displayOverlay = false;
                resolve();
            }, 2500);
        });
    },
    methods: {
        async getBlog() {
            const id = `${this.$route.params.blogId}`;
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.tempBlogData = response.data;

                    this.imageName = this.tempBlogData.image
                        ? this.tempBlogData.image.slice(
                              this.tempBlogData.image.indexOf("uploads/") + 8
                          )
                        : null;
                }
            });
        },
        updateBlog() {
            this.descError = null;
            if (this.blogData.description == "") {
                this.descError = "This field is required.";
            } else if (this.blogData.description.length < 50) {
                this.descError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("image", this.file);
                data.append("title", this.blogData.title);
                data.append("description", this.blogData.description);
                data.append("author_name", this.blogData.author_name);
                data.append("categories", this.blogData.categoryIds);
                data.append("meta_title", this.blogData.meta_title);
                data.append("meta_description", this.blogData.meta_description);
                data.append("meta_keywords", this.blogData.meta_keywords);
                update(data, this.blogData.id)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
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
            this.blogData.meta_title = this.blogData.title;
            this.blogData.meta_description = this.blogData.title;
            this.blogData.meta_keywords = this.blogData.title;
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
