<template>
    <div class="form-box">
        <vee-form
            :validation-schema="schema"
            @submit="updateClinician"
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
                            v-model="clinicianData.name"
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
                            v-model="clinicianData.qualification"
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
                            v-model="clinicianData.email"
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
                            name="phone_no"
                            type="text"
                            v-model="clinicianData.phone_no"
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
                                name="phone_no"
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
                                        <small v-if="clinicianData.imageName">
                                            {{ clinicianData.imageName }}
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

                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="bio"
                            type="text"
                        >
                            <ckeditor
                                :editor="editor"
                                v-model="clinicianData.bio"
                                :config="editorConfig"
                            ></ckeditor>
                            <div class="error invalid-feedback">
                                {{ bioError }}
                            </div>
                        </vee-field>
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
                            v-model="clinicianData.specialties"
                        >
                            <Multiselect
                                v-model="clinicianData.specialties"
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
                        <label for="">States covered <sup>*</sup></label>
                        <vee-field
                            v-slot="{ field, errors }"
                            class="form-control"
                            name="states"
                            type="text"
                            v-model="clinicianData.states"
                        >
                            <Multiselect
                                v-model="clinicianData.states"
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
                            v-model="clinicianData.services"
                        >
                            <Multiselect
                                v-model="clinicianData.services"
                                mode="tags"
                                v-bind="field"
                                :close-on-select="false"
                                :searchable="true"
                                :create-option="false"
                                valueProp="id"
                                label="name"
                                :required="true"
                                :options="services"
                                placeholder="Select Services"
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
                <!-- <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Insurance</label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input
                                    class="form-check-input mt-0"
                                    type="checkbox"
                                    v-model="clinicianData.is_insurance"
                                    value=""
                                />
                            </div>
                            <select
                                name=""
                                id=""
                                class="form-select form-control"
                            >
                                <option value="">Please Select</option>
                            </select>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-4">
                    <div class="form-field">
                        <label for="">Charges Per Session<sup>*</sup></label>
                        <div class="input-group">
                            <div class="input-group-text">$</div>
                            <vee-field
                                name="charges_per_session"
                                type="number"
                                v-model="clinicianData.charges_per_session"
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
                            v-model="clinicianData.azalea_id"
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
                            v-model="clinicianData.tru_billing_id"
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
                                    v-model="clinicianData.locations"
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
                            v-model="clinicianData.ageRanges"
                        >
                            <Multiselect
                                v-model="clinicianData.ageRanges"
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
                            v-model="clinicianData.is_insurance"
                            @change="addSelectedInsurance()"
                        >
                            <option value="" disabled>
                                Select {{ clinicianData.is_insurance }}
                            </option>
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
                <div class="col-12">
                    <div
                        class="row mb-2 g-3 align-items-center"
                        v-for="(input, index) in selectedInsurances"
                    >
                        <div class="col-sm">
                            <Multiselect
                                v-model="
                                    clinicianData.selectedInsurances[index]
                                        .insurance_id
                                "
                                mode="single"
                                v-bind="field"
                                :close-on-select="true"
                                :searchable="true"
                                :create-option="false"
                                valueProp="insurance_id"
                                label="name"
                                :required="true"
                                :options="insurances"
                                placeholder="Select Insurance"
                                @change="onInsuranceChange(index)"
                            />
                        </div>
                        <div class="col-sm">
                            <Multiselect
                                v-model="
                                    formData.selectedInsurances[index].plan_id
                                "
                                mode="single"
                                v-bind="field"
                                :close-on-select="true"
                                :searchable="true"
                                :create-option="false"
                                valueProp="plan_id"
                                label="planname"
                                :required="true"
                                :options="insurancePlans[index]"
                                placeholder="Select Plan"
                            />
                        </div>
                        <div class="col-sm-auto" style="min-width: 100px">
                            <div class="row">
                                <div class="col-auto col-sm">
                                    <a
                                        @click="deleteRow(index)"
                                        v-if="index > 0"
                                        href="javascript:void(0);"
                                    >
                                        <i
                                            class="bi bi-trash text-danger fs-4"
                                        ></i>
                                    </a>
                                </div>
                                <div
                                    class="col-auto col-sm"
                                    v-if="formData.is_insurance == '1'"
                                >
                                    <a
                                        @click="addRow"
                                        href="javascript:void(0);"
                                    >
                                        <i
                                            class="bi bi-plus-circle text-success fs-4"
                                        ></i>
                                    </a>
                                </div>
                            </div>
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
                            v-model="clinicianData.meta_title"
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
                            v-model="clinicianData.meta_description"
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
                            v-model="clinicianData.meta_keywords"
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
import { update } from "@/api/clinician";
export default {
    props: {
        //
        states: {
            type: Array,
            default: [],
        },
        specialties: {
            type: Array,
            default: [],
        },
        age_ranges: {
            type: Array,
            default: [],
        },
        services: {
            type: Array,
            default: [],
        },
        locations: {
            type: Array,
            default: [],
        },
        clinicianData: {
            type: Object,
            default: {},
        },
    },
    data() {
        return {
            image: null,
            dragging: false,
            locationErr: null,
            bioError: null,
            schema: {
                name: "required|min:1|max:50|regex:^([^0-9]*)$",
                qualification: "required|min:3",
                // bio: "required|min:50",
                azalea_id: "required|min:1|max:100|numeric",
                tru_billing_id: "required|min:3|max:12",
                states: "required",
                // email: "required|email",
                // phone_no: "required|min:7|max:15|numeric",
                ages_covered: "required",
                specialities: "required",
                services: "required",
                charges_per_session: "required|min_value:1|numeric",
            },
        };
    },
    methods: {
        deleteRow(index) {
            this.selectedInsurances.splice(index, 1);
            this.formData.selectedInsurances.splice(index, 1);
        },
        addRow() {
            const selectedInsurancesLength = this.selectedInsurancesLength;
            if (
                this.formData.selectedInsurances[selectedInsurancesLength - 1]
                    .insurance_id == ""
            ) {
                this.$swal({
                    title: "Please fill last row's details first",
                    showCancelButton: false,
                    icon: "",
                    confirmButtonText: "Ok",
                });
            } else {
                this.addSelectedInsurance();
            }
        },
        addSelectedInsurance() {
            if (this.clinicianData.is_insurance == "1") {
                this.clinicianData.selectedInsurances.push({
                    insurance_id: "",
                    plan_id: "",
                });
                this.selectedInsurances.push({
                    insurance_id: "",
                    plan_id: "",
                });
                this.selectedInsurancesLength =
                    this.clinicianData.selectedInsurances.length;
            }
            if (this.clinicianData.is_insurance == "0") {
                this.clinicianData.selectedInsurances = [];
                this.selectedInsurances = [];
                this.selectedInsurancesLength =
                    this.clinicianData.selectedInsurances.length;
            }
        },
        updateClinician() {
            this.locationErr = null;
            this.bioError = null;
            if (this.clinicianData.locations.length == 0) {
                this.locationErr = "Please select atleast 1 location.";
            } else if (this.clinicianData.bio == "") {
                this.bioError = "This field is required.";
            } else if (this.clinicianData.bio.length < 50) {
                this.bioError = "Minimum 50 characters are allowed.";
            } else {
                let data = new FormData();
                data.append("image", this.clinicianData.image);
                data.append("name", this.clinicianData.name);
                data.append("qualification", this.clinicianData.qualification);
                data.append("bio", this.clinicianData.bio);
                data.append("specialities", this.clinicianData.specialties);
                data.append("ages_covered", this.clinicianData.ageRanges);
                data.append("services", this.clinicianData.services);
                data.append("states", this.clinicianData.states);
                data.append("email", this.clinicianData.email);
                data.append("phone_no", this.clinicianData.phone_no);
                data.append("locations", this.clinicianData.locations);
                data.append("azalea_id", this.clinicianData.azalea_id);
                data.append("meta_title", this.clinicianData.meta_title);
                data.append(
                    "meta_description",
                    this.clinicianData.meta_description
                );
                data.append("meta_keywords", this.clinicianData.meta_keywords);
                data.append(
                    "charges_per_session",
                    this.clinicianData.charges_per_session
                );
                data.append(
                    "tru_billing_id",
                    this.clinicianData.tru_billing_id
                );
                data.append(
                    "is_insurance",
                    this.clinicianData.is_insurance == false ? 0 : 1
                );
                update(data, this.clinicianData.id)
                    .then((response) => {
                        this.handleResponse(response);
                        if (response.success == true) {
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
            this.clinicianData.meta_title = this.clinicianData.name;
            this.clinicianData.meta_description = this.clinicianData.name;
            this.clinicianData.meta_keywords = this.clinicianData.name;
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
            this.clinicianData.imageName = file.name;
            this.clinicianData.image = file;
            this.dragging = false;
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
