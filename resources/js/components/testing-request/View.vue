<template>
    <div data-v-11c9b35a="" id="view_order">
        <div class="row gy-2 pa-4">
            <div class="col-lg-6 col-12">
                <h3 class="mb-0">
                    {{ testingRequest.first_name }}
                    {{ testingRequest.last_name }}
                </h3>
            </div>
            <div class="col-12">
                <b>Date of Birth:</b>
                {{ $dayjs(testingRequest.dob).format("DD MMM, YYYY") }}
            </div>
            <div class="col-12">
                <b> Email: </b>
                {{ testingRequest.email }}
            </div>
            <div class="col-12">
                <b> Home Phone: </b>
                {{ testingRequest.home_phone }}
            </div>
            <div class="col-12">
                <b> Mobile No.: </b>
                {{ testingRequest.mobile_number }}
            </div>
            <div class="col-12" v-if="testingRequest.insurance"><hr /></div>
            <div class="col-12" v-if="testingRequest.insurance">
                <h4>Insurance Details:</h4>
                <b>Insurance:</b>
                {{
                    testingRequest.insurance
                        ? testingRequest.insurance.name
                        : "N/A"
                }}
            </div>
            <div class="col-12" v-if="testingRequest.insurance">
                <b>Insurance Plan:</b>
                {{ testingRequest.plan ? testingRequest.plan.name : "N/A" }}
            </div>
            <div class="col-12" v-if="testingRequest.insurance">
                <b>Member ID/EAP Auth:</b>
                {{ testingRequest.member_id ?? "N/A" }}
            </div>
            <div class="col-12" v-if="testingRequest.insurance">
                <b>Policy Holder's Gender:</b>
                {{
                    testingRequest.policy_holder_gender
                        ? testingRequest.policy_holder_gender.toUpperCase()
                        : "N/A"
                }}
            </div>
            <div class="col-12" v-if="testingRequest.insurance">
                <b>Testing Type:</b>
                {{
                    testingRequest.testing_type
                        ? testingRequest.testing_type.name
                        : "N/A"
                }}
            </div>
            <div class="col-12" v-if="testingRequest.insurance">
                <b>Relation to Patient:</b>
                {{ testingRequest.relation_to_patient ?? "N/A" }}
            </div>
            <div class="col-12" v-if="testingRequest.insurance">
                <b>Clinician Preference:</b>
                {{ testingRequest.clinician_preference ?? "N/A" }}
            </div>
            <div class="col-12"><hr /></div>
            <div class="col-12">
                <b>Message</b>
                {{ testingRequest.message ?? "N/A" }}
            </div>
        </div>
    </div>
</template>
<script>
import { fetch } from "@/api/testingRequest";
export default {
    data() {
        return {
            testingRequest: {},
        };
    },
    methods: {
        getTestingRequest() {
            const id = this.$route.params.requestId;
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.testingRequest = response.data;
                }
            });
        },
    },
    mounted() {
        this.getTestingRequest();
    },
};
</script>
<style scoped></style>
