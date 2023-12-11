<template>
    <div class="overlay" v-if="displayOverlay">
        <div class="overlay__inner">
            <div class="overlay__content">
                <span class="spinner"></span>
            </div>
        </div>
    </div>
    <section class="section-content bg-white col p-4">
        <Breadcrumb :crumbs="breadcrumbs" />
        <div class="title-box">
            <div class="row gy-3">
                <LeftSideHeader headerData="Edit Clinician" />
                <RightSideButton :btnData="btnData" />
            </div>
        </div>
        <div class="content-wrap pt-4">
            <Edit
                :clinicianData="clinicianData"
                :states="states"
                :age_ranges="age_ranges"
                :specialties="specialties"
                :services="services"
                :locations="locations"
            />
        </div>
    </section>
</template>

<script>
import Edit from "@/components/clinician/Edit.vue";
import Breadcrumb from "@/components/includes/Breadcrumb.vue";
import RightSideButton from "@/components/includes/RightSideButton.vue";
import LeftSideHeader from "@/components/includes/LeftSideHeader.vue";
import { fetchList } from "@/api/states";
import { fetchList as fetchAgeRange } from "@/api/ageRange";
import { fetchList as fetchLocation } from "@/api/location";
import { fetchList as fetchSpeciality } from "@/api/speciality";
import { fetchList as fetchService } from "@/api/servicesProvided";
import { fetch } from "@/api/clinician";
import { fetchList as fetchInsurances } from "@/api/insurance";

export default {
    name: "Cinicians",
    components: {
        Edit,
        Breadcrumb,
        RightSideButton,
        LeftSideHeader,
    },
    data() {
        return {
            btnData: {
                title: "All Clinicians",
                url: "/clinicians/list",
            },
            clinician: {},
            clinicianData: {},
            states: [],
            age_ranges: [],
            locations: [],
            specialties: [],
            services: [],
            insurances: [],
            displayOverlay: true,
            insurancePlans: [],
            selectedInsurances: [],
            selectedInsurancesLength: 0,
            formDataSelectedInsurances: [],
            breadcrumbs: [
                {
                    class: null,
                    url: "/clinicians/list",
                    name: "Our Clinicians",
                },
                {
                    class: "active",
                    url: "",
                    name: "Edit",
                },
            ],
        };
    },
    async mounted() {
        this.displayOverlay = true;
        await this.getStates();
        await this.getAgeRanges();
        await this.getSpecialties();
        await this.getServices();
        await this.getLocations();
        await this.getClinicianRecord();
        await this.getInsurances();

        let that = this;
        new Promise(function (resolve, reject) {
            setTimeout(() => {
                that.clinicianData = that.clinician;
                that.displayOverlay = false;
                resolve();
            }, 4500);
        });
    },
    methods: {
        async getStates() {
            fetchList("?active=true").then((response) => {
                this.states = response.data;
            });
        },
        async getInsurances() {
            await fetchInsurances("?active=true").then((response) => {
                this.insurances = response.data.data;
            });
        },
        async getAgeRanges() {
            fetchAgeRange("?active=true").then((response) => {
                this.age_ranges = response.data.data;
            });
        },
        async getSpecialties() {
            fetchSpeciality("?active=true").then((response) => {
                this.specialties = response.data.data;
            });
        },
        async getLocations() {
            await fetchLocation("?active=true").then((response) => {
                this.locations = response.data;
            });
        },
        async getServices() {
            fetchService("?active=true").then((response) => {
                this.services = response.data.data;
            });
        },
        async getClinicianRecord() {
            const id = `${this.$route.params.clinicianId}`;
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.clinician = response.data;
                    let states = [];
                    let ageRanges = [];
                    let specialties = [];
                    let services = [];
                    let locations = [];
                    response.data.services_provided.forEach((service) => {
                        services.push(service.service_provided_id);
                    });
                    response.data.age_covered.forEach((ageRange) => {
                        ageRanges.push(ageRange.age_range_id);
                    });
                    response.data.locations.forEach((location) => {
                        locations.push(location.location_id);
                    });
                    response.data.specialties.forEach((specialty) => {
                        specialties.push(specialty.speciality_id);
                    });
                    response.data.states.forEach((state) => {
                        states.push(state.state_id);
                    });
                    this.clinician.services = services;
                    this.clinician.locations = locations;
                    this.clinician.ageRanges = ageRanges;
                    this.clinician.specialties = specialties;
                    this.clinician.imageName = this.clinician.image
                        ? this.clinician.image.slice(
                              this.clinician.image.indexOf("uploads/") + 8
                          )
                        : null;
                    this.clinician.states = states;
                }
            });
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
</style>
