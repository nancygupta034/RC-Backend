<template>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="addClinician"
            enctype="multipart/form-data"
        >
            <div class="row">
                <div class="col-md-12">
                    <h4 class="subHeading">Basic Details</h4>
                </div>
                <div class="col-md-6">
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
                        <label for="">Qualifications<sup>*</sup></label>
                        <vee-field
                            name="qualification"
                            type="text"
                            v-model="formData.qualification"
                            :validateOnModelUpdate="false"
                            :validateOnBlur="false"
                            :validateOnChange="false"
                            :validateOnInput="false"
                            :validateOnMount="false"
                            class="form-control"
                            placeholder="Enter Qualifications"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="qualification"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Email</label>
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
                <div class="col-md-6">
                    <div class="form-field">
                        <label for="">Phone No.</label>
                        <vee-field
                            name="phone"
                            type="text"
                            v-model="formData.phone"
                            :validateOnModelUpdate="false"
                            :validateOnBlur="false"
                            :validateOnChange="false"
                            :validateOnInput="false"
                            :validateOnMount="false"
                            class="form-control"
                            placeholder="Enter Phone No."
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="phone"
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
                        <label for="">Full Bio<sup>*</sup></label>
                        <ckeditor
                            :editor="editor"
                            v-model="formData.bio"
                            :config="editorConfig"
                        ></ckeditor>
                        <div class="error invalid-feedback">
                            {{ bioError }}
                        </div>
                        <!-- <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="bio"
                            type="text"
                            v-model="formData.bio"
                        >
                            <textarea
                                name="bio"
                                v-bind="field"
                                id=""
                                cols="30"
                                rows="6"
                                class="form-control h-auto"
                                placeholder="Enter Bio"
                            ></textarea>
                            <div
                                class="error invalid-feedback"
                                v-if="errors[0]"
                            >
                                {{ errors[0] }}
                            </div>
                        </vee-field> -->
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Specialities<sup>*</sup></label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="specialities"
                            type="text"
                            v-model="formData.specialities"
                        >
                            <Multiselect
                                v-model="formData.specialities"
                                mode="tags"
                                v-bind="field"
                                :close-on-select="false"
                                :searchable="true"
                                :create-option="false"
                                valueProp="id"
                                label="name"
                                :required="true"
                                :options="specialties"
                                placeholder="Select Specialities"
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
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">States covered<sup>*</sup></label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="states"
                            type="text"
                            v-model="formData.states"
                        >
                            <Multiselect
                                v-model="formData.states"
                                mode="tags"
                                v-bind="field"
                                :close-on-select="false"
                                :searchable="true"
                                :create-option="false"
                                valueProp="id"
                                label="name"
                                :required="true"
                                :options="states"
                                placeholder="Select States"
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

                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Services Provided<sup>*</sup></label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="services"
                            type="text"
                            v-model="formData.services"
                        >
                            <Multiselect
                                v-model="formData.services"
                                mode="tags"
                                v-bind="field"
                                :close-on-select="false"
                                :searchable="true"
                                :create-option="false"
                                valueProp="id"
                                label="name"
                                :required="true"
                                placeholder="Select Services"
                                :options="services"
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
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Charges Per Session<sup>*</sup></label>
                        <div class="input-group">
                            <div class="input-group-text">$</div>
                            <vee-field
                                name="charges_per_session"
                                type="number"
                                v-model="formData.charges_per_session"
                                :validateOnModelUpdate="false"
                                :validateOnBlur="false"
                                :validateOnChange="false"
                                :validateOnInput="false"
                                :validateOnMount="false"
                                class="form-control"
                                placeholder="Enter Charges Per Session"
                            />
                        </div>

                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="charges_per_session"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Azalea ID<sup>*</sup></label>
                        <vee-field
                            name="azalea_id"
                            type="text"
                            v-model="formData.azalea_id"
                            :validateOnModelUpdate="false"
                            :validateOnBlur="false"
                            :validateOnChange="false"
                            :validateOnInput="false"
                            :validateOnMount="false"
                            class="form-control"
                            placeholder="Enter Azalea ID"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="azalea_id"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Tru Billing ID<sup>*</sup></label>
                        <vee-field
                            name="tru_billing_id"
                            type="text"
                            v-model="formData.tru_billing_id"
                            :validateOnModelUpdate="false"
                            :validateOnBlur="false"
                            :validateOnChange="false"
                            :validateOnInput="false"
                            :validateOnMount="false"
                            class="form-control"
                            placeholder="Enter Tru Billing ID"
                        />
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="tru_billing_id"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Location<sup>*</sup></label>
                        <div class="row mt-2">
                            <div
                                class="col-auto"
                                v-for="(location, index) in locations"
                                :key="index"
                            >
                                <input
                                    class="form-check-input mt-0"
                                    type="checkbox"
                                    v-model="formData.locations"
                                    :value="location.id"
                                />
                                {{ location.name }}
                            </div>
                        </div>
                        <div class="error invalid-feedback">
                            {{ locationErr }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Ages covered<sup>*</sup></label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="ages_covered"
                            type="text"
                            v-model="formData.ages_covered"
                        >
                            <Multiselect
                                v-model="formData.ages_covered"
                                mode="tags"
                                v-bind="field"
                                :close-on-select="false"
                                :searchable="true"
                                :create-option="false"
                                valueProp="id"
                                label="text"
                                :required="true"
                                :options="age_ranges"
                                placeholder="Select Age Ranges"
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
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Accepts Insurance<sup>*</sup></label>
                        <vee-field
                            as="select"
                            v-slot="{ field, errors }"
                            class="form-select"
                            name="is_insurance"
                            type="text"
                            v-model="formData.is_insurance"
                        >
                            <option value="" disabled>Select</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </vee-field>
                        <div class="error invalid-feedback">
                            <ErrorMessage
                                class="invalid-feedback"
                                name="is_insurance"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
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
import { fetchList } from "@/api/states";
import { fetchList as fetchAgeRange } from "@/api/ageRange";
import { fetchList as fetchLocation } from "@/api/location";
import { fetchList as fetchInsurances } from "@/api/insurance";
import { fetchList as fetchInsurancePlans } from "@/api/insurancePlan";
import { fetchList as fetchSpeciality } from "@/api/speciality";
import { fetchList as fetchService } from "@/api/servicesProvided";
import { create } from "@/api/clinician";
export default {
    data() {
        return {
            bioError: null,
            insurancePlans: [],
            locationErr: null,
            imageName: "",
            dragging: false,
            states: [],
            insurances: [],
            age_ranges: [],
            specialties: [],
            services: [],
            locations: [],
            image: null,
            formData: {
                name: null,
                qualification: null,
                image: null,
                bio: "",
                email: null,
                phone: null,
                specialities: [],
                ages_covered: [],
                services: [],
                locations: [],
                states: [],
                azalea_id: null,
                tru_billing_id: null,
                is_insurance: "0",
                meta_title: null,
                meta_description: null,
                meta_keywords: null,
                charges_per_session: 0,
            },
            schema: {
                name: "required|min:1|max:50|regex:^([^0-9]*)$",
                qualification: "required|min:3",
                azalea_id: "required|min:1|max:100|numeric",
                tru_billing_id: "required|min:3|max:12",
                states: "required",
                ages_covered: "required",
                specialities: "required",
                services: "required",
                charges_per_session: "required|min_value:1|numeric",
            },
        };
    },
    mounted() {
        this.getStates();
        this.getAgeRanges();
        this.getSpecialties();
        this.getServices();
        this.getLocations();
    },
    methods: {
        getStates() {
            fetchList("?active=true").then((response) => {
                this.states = response.data;
            });
        },
        async getLocations() {
            await fetchLocation("?active=true").then((response) => {
                this.locations = response.data;
            });
        },
        getAgeRanges() {
            fetchAgeRange("?active=true").then((response) => {
                this.age_ranges = response.data.data;
            });
        },
        getSpecialties() {
            fetchSpeciality("?active=true").then((response) => {
                this.specialties = response.data.data;
            });
        },
        getServices() {
            fetchService("?active=true").then((response) => {
                this.services = response.data.data;
            });
        },
        addClinician(values, { resetForm }) {
            this.locationErr = null;
            this.bioError = null;
            if (this.formData.locations.length == 0) {
                this.locationErr = "Please select atleast 1 location.";
            } else if (this.formData.bio == "") {
                this.bioError = "This field is required.";
            } else if (this.formData.bio.length < 50) {
                this.bioError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("image", this.file);
                data.append("name", this.formData.name);
                data.append("qualification", this.formData.qualification);
                data.append("bio", this.formData.bio);
                data.append("specialities", this.formData.specialities);
                data.append("ages_covered", this.formData.ages_covered);
                data.append("services", this.formData.services);
                data.append("states", this.formData.states);
                data.append("email", this.formData.email);
                data.append("phone_no", this.formData.phone);
                data.append("locations", this.formData.locations);
                data.append("azalea_id", this.formData.azalea_id);
                data.append("tru_billing_id", this.formData.tru_billing_id);
                data.append("meta_title", this.formData.meta_title);
                data.append("meta_description", this.formData.meta_description);
                data.append("meta_keywords", this.formData.meta_keywords);
                data.append(
                    "charges_per_session",
                    this.formData.charges_per_session
                );
                data.append(
                    "is_insurance",
                    this.formData.is_insurance == "0" ? 0 : 1
                );
                create(data)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
                            this.file = "";
                            resetForm();
                            this.$router.push({
                                path: `/clinicians/list`,
                            });
                        }
                    })
                    .catch((error) => {
                        this.handleCatch(error);
                    });
            }
        },
        onChangeOfTitle() {
            this.formData.meta_title = this.formData.name;
            this.formData.meta_description = this.formData.name;
            this.formData.meta_keywords = this.formData.name;
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
