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
            @submit="updateTeam"
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
                            v-model="teamData.name"
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
                            v-model="teamData.designation"
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
                            v-model="teamData.email"
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
                            v-model="teamData.description"
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
                            v-model="teamData.meta_title"
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
                            v-model="teamData.meta_description"
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
                            v-model="teamData.meta_keywords"
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
<script>
import { fetch, update } from "@/api/team";
export default {
    data() {
        return {
            displayOverlay: true,
            imageName: "",
            dragging: false,
            image: null,
            teamData: {
                name: null,
                image: null,
                description: "",
                email: null,
                designation: null,
                meta_title: null,
                meta_description: null,
                meta_keywords: null,
            },
            tempTeamData: {},
            schema: {
                name: "required|min:1|max:300|regex:^([^0-9]*)$",
                email: "required|email",
                designation: "required",
            },
            descError: null,
        };
    },
    async mounted() {
        await this.getBlog();
        let that = this;
        new Promise(function (resolve, reject) {
            setTimeout(() => {
                that.teamData = that.tempTeamData;
                that.displayOverlay = false;
                resolve();
            }, 2500);
        });
    },
    methods: {
        async getBlog() {
            const id = `${this.$route.params.teamId}`;
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.tempTeamData = response.data;

                    this.imageName = this.tempTeamData.image
                        ? this.tempTeamData.image.slice(
                              this.tempTeamData.image.indexOf("uploads/") + 8
                          )
                        : null;
                }
            });
        },
        updateTeam() {
            this.descError = null;
            if (this.teamData.description == "") {
                this.descError = "This field is required.";
            } else if (this.teamData.description.length < 50) {
                this.descError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("image", this.file);
                data.append("name", this.teamData.name);
                data.append("email", this.teamData.email);
                data.append("designation", this.teamData.designation);
                data.append("description", this.teamData.description);
                data.append("meta_title", this.teamData.meta_title);
                data.append("meta_description", this.teamData.meta_description);
                data.append("meta_keywords", this.teamData.meta_keywords);
                update(data, this.teamData.id)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
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
            this.teamData.meta_title = this.teamData.name;
            this.teamData.meta_description = this.teamData.name;
            this.teamData.meta_keywords = this.teamData.name;
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
