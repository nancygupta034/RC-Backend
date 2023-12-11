<template>
    <div
        class="row g-4"
        v-if="Object.keys(appointment).length !== 0 && !recordNotFound"
    >
        <div class="col-12">
            <div class="blueBox">
                <div class="row align-items-center">
                    <div class="col-12 col-md">
                        <div class="aInfo">
                            <h4>
                                {{
                                    $dayjs(appointment.date).format(
                                        "MMM DD, YYYY"
                                    )
                                }}
                                @ {{ appointment.time }}
                            </h4>
                            <h4 class="price">
                                Charges: ${{
                                    appointment.clinician.charges_per_session
                                }}
                            </h4>
                            <div class="Astatus pending mt-3">
                                Status: {{ appointment.status }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="col-12 col-md-auto pt-4 pt-lg-0"
                        v-if="appointment.clinician"
                    >
                        <h6>Appointment with</h6>
                        <div class="cBox">
                            <div class="row g-3">
                                <div class="col-auto">
                                    <div class="cImg">
                                        <img
                                            :src="`${appointment.clinician.image}`"
                                            alt=""
                                        />
                                    </div>
                                </div>
                                <div class="col">
                                    <h3>
                                        {{ appointment.clinician.name }}
                                        <span>{{
                                            appointment.clinician.qualification
                                        }}</span>
                                    </h3>
                                    <div class="cInfo">
                                        {{ appointment.clinician.email }}<br />
                                        {{ appointment.clinician.phone_no }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12" v-if="appointment.referral">
            <div class="greyBox">
                <div class="row">
                    <div class="col-12 col-lg-6 d-flex">
                        <div>
                            <h4>Doctor's Details</h4>
                            <ul>
                                <li>
                                    <span>Name:</span>
                                    {{ appointment.referral.dr_name }}
                                </li>
                                <li>
                                    <span>Email:</span>
                                    {{ appointment.referral.dr_email }}
                                </li>
                                <li>
                                    <span>Mobile No.:</span>
                                    {{ appointment.referral.dr_mobile_number }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 d-flex">
                        <div>
                            <h4>Clinic's Details</h4>
                            <ul>
                                <li>
                                    <span>Name:</span>
                                    {{ appointment.referral.clinic_name }}
                                </li>
                                <li>
                                    <span>Email:</span>
                                    {{ appointment.referral.clinic_email }}
                                </li>
                                <li>
                                    <span>Phone No.:</span>
                                    {{
                                        appointment.referral
                                            .clinic_mobile_number
                                    }}
                                </li>
                                <li>
                                    <span>Address:</span>
                                    {{ appointment.referral.clinic_address }}
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr v-if="appointment.referral.reason" />
                    <div class="col-12" v-if="appointment.referral.reason">
                        <div>
                            <h4>Reason</h4>
                            {{ appointment.referral.reason }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 d-flex">
            <div class="greyBox">
                <h4>Personal Details</h4>
                <ul>
                    <li>
                        <span>Name:</span> {{ appointment.first_name }}
                        {{ appointment.last_name }}
                    </li>
                    <li>
                        <span>Date of Birth:</span>
                        {{ $dayjs(appointment.dob).format("MMM DD, YYYY") }}
                    </li>
                    <li><span>Email:</span> {{ appointment.email }}</li>
                    <li>
                        <span>Home Phone:</span> {{ appointment.home_phone }}
                    </li>
                    <li>
                        <span>Mobile No.:</span> {{ appointment.mobile_number }}
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-12 col-lg-6 d-flex">
            <div class="greyBox">
                <h4>Payment Info</h4>
                <ul>
                    <li>
                        <span>Payment Mode:</span>
                        {{ appointment.payment_mode }}
                    </li>
                    <li v-if="appointment.insurance_id">
                        <span>Insurance:</span>
                        {{
                            appointment.insurance
                                ? appointment.insurance.name
                                : "N/A"
                        }}
                    </li>
                    <li v-if="appointment.insurance_plan_id">
                        <span>Insurance Plan:</span>
                        {{
                            appointment.plan ? appointment.plan.planname : "N/A"
                        }}
                    </li>
                    <li v-if="appointment.member_id">
                        <span>Member ID/EAP Auth:</span>
                        {{ appointment.member_id }}
                    </li>
                    <li v-if="appointment.policy_holder_gender">
                        <span>Policy Holder's Gender:</span>
                        {{ appointment.policy_holder_gender }}
                    </li>
                    <li v-if="appointment.service_provided">
                        <span>Service:</span>
                        {{ appointment.service_provided.name }}
                    </li>
                    <li v-if="appointment.relation_to_patient">
                        <span>Relation to Patient:</span>
                        {{ appointment.relation_to_patient }}
                    </li>
                </ul>
            </div>
        </div>
        <div
            class="col-12 px-4"
            v-if="
                appointment.cancellation_reason &&
                appointment.cancellation_reason != 'null'
            "
        >
            <h6>Reason for Cancelling Appointment:</h6>
            <p>{{ appointment.cancellation_reason }}</p>
        </div>
    </div>
    <div class="row g-4" v-else>No record found</div>
</template>
<script>
import { fetch } from "@/api/appointment";
export default {
    components: {},
    data() {
        return {
            appointment: {},
            recordNotFound: false,
        };
    },
    methods: {
        getAppointment() {
            this.recordNotFound = false;
            const id = this.$route.params.appointmentId;
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.appointment = response.data;
                } else {
                    this.recordNotFound = true;
                }
            });
        },
    },
    mounted() {
        this.getAppointment();
    },
};
</script>
<style scoped></style>
