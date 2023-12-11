<template>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Reference No.</th>
                    <th>Name</th>
                    <th>Clinician</th>
                    <th v-if="type == 'referral'">Referral Dr. Name</th>
                    <th v-if="type == 'referral'">Type</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody v-if="appointments.length">
                <tr v-for="(appointment, index) in appointments" :key="index">
                    <td>#{{ appointment.reference_id }}</td>
                    <td>
                        {{ appointment.first_name }} {{ appointment.last_name
                        }}<br />
                        <small
                            >Mobile No.: {{ appointment.mobile_number }}</small
                        >
                    </td>
                    <td>
                        <div class="row flex-nowrap align-items-center">
                            <div class="col">
                                <div class="user-info">
                                    <strong>{{
                                        appointment.clinician
                                            ? appointment.clinician.name
                                            : "N/A"
                                    }}</strong>
                                    <small>{{
                                        appointment.clinician
                                            ? appointment.clinician
                                                  .qualification
                                            : "N/A"
                                    }}</small>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td v-if="type == 'referral'">
                        {{
                            appointment.referral
                                ? appointment.referral.dr_name
                                : "N/A"
                        }}
                    </td>
                    <td v-if="type == 'referral'">
                        {{
                            appointment.referral
                                ? appointment.referral.type
                                : "N/A"
                        }}
                    </td>
                    <td>
                        {{ $dayjs(appointment.date).format("MMM DD, YYYY") }}
                    </td>
                    <td>
                        {{ appointment.status }}
                    </td>
                    <td style="white-space: nowrap">
                        <ul class="actions">
                            <li>
                                <router-link
                                    :to="`/${type}-appointments/${appointment.id}`"
                                    class="btn btn-link"
                                    title="View"
                                    alt="View"
                                >
                                    <i class="bi bi-eye fs-5" title="View"></i>
                                </router-link>
                            </li>
                            <li
                                @click="getAppointmentInfo(appointment.id)"
                                v-if="
                                    type == 'confirmed' &&
                                    appointment.status != 'Cancelled' &&
                                    appointment.status != 'Arrived' &&
                                    appointment.status != 'Fulfilled' &&
                                    $dayjs(appointment.date).isAfter(
                                        $dayjs(currentDate)
                                    )
                                "
                            >
                                <button
                                    data-fancybox
                                    data-src="#dialog-content"
                                    class="btn btn-link text-success"
                                    title="Reschedule"
                                    alt="Reschedule"
                                >
                                    <i
                                        class="bi bi-arrow-clockwise fs-5"
                                        title="Reschedule"
                                        alt="Reschedule"
                                    ></i>
                                </button>
                            </li>
                            <li
                                @click="
                                    appointmentId = appointment.id;
                                    statusAppointment = appointment;
                                "
                                v-if="
                                    appointment.status != 'Cancelled' &&
                                    $dayjs(appointment.date).isAfter(
                                        $dayjs(currentDate)
                                    )
                                "
                            >
                                <button
                                    data-fancybox
                                    data-src="#status-dialog-content"
                                    class="btn btn-link text-danger"
                                    title="Update Status"
                                    alt="Update Status"
                                >
                                    <i
                                        class="bi bi-pencil-square fs-5"
                                        style="
                                            font-size: 17px !important;
                                            line-height: 32px;
                                        "
                                        title="Update Status"
                                        alt="Update Status"
                                    ></i>
                                </button>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center">
                        No appointment scheduled yet
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row gy-4 align-items-center">
        <div class="col-auto"></div>
        <div class="col-auto ms-auto">
            <div class="row align-items-center justify-content-end">
                <div class="col-auto">Page</div>
                <div class="col ps-0">
                    <nav class="pagination-wrap">
                        <ul class="pagination flex-wrap pagination-sm mb-0">
                            <div v-for="pageNo in totalCount">
                                <li class="page-item">
                                    <btn
                                        @click="changePage(pageNo)"
                                        class="page-link"
                                        href="#"
                                        >{{ pageNo }}</btn
                                    >
                                </li>
                            </div>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div id="dialog-content" class="popup">
        <h2>Reschedule Appointment</h2>
        <button
            data-fancybox-close=""
            class="f-button is-close-btn"
            @click="closeModal()"
            title="Close"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                tabindex="-1"
            >
                <path d="M20 20L4 4m16 0L4 20"></path>
            </svg>
        </button>
        <!-- <button @click="closeModal()">Close</button> -->
        <div class="overlay" v-if="displayOverlay">
            <div class="overlay__inner">
                <div class="overlay__content">
                    <span class="spinner"></span>
                </div>
            </div>
        </div>
        <div class="card card-3 mt-4">
            <vee-field
                v-slot="{ field, errors }"
                class="form-control"
                name="categories"
                type="text"
            >
                <label for="">Select Date<span>*</span></label>
                <flat-pickr
                    v-model="date"
                    :config="config"
                    @change="getClinicianSlots"
                    placeholder="Select date"
                />
                <div class="error invalid-feedback" v-if="errors[0]">
                    {{ errors[0] }}
                </div>
            </vee-field>

            <div class="card-footer mt-4" v-if="slots.length !== 0">
                <div class="card-ft">Available Appointment Slots</div>
                <div class="card-ftwrp">
                    <div
                        class="card-ftlist"
                        v-for="(slot, index) in slots"
                        :key="index"
                    >
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="card-fdate">
                                    {{ slot.date }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="card-fbtn">
                                    <a
                                        class="btn btn-sm btn-secondary"
                                        @click.prevent="
                                            reschedule(slot, appointment.id)
                                        "
                                        >Book Now
                                        <i
                                            class="bi bi-arrow-right-short ms-2"
                                        ></i
                                    ></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-warning rounded-3 p-4 mt-4" v-if="noSlots">
                Unfortunately, no slots available for selected date. Please
                check another one.
            </div>
        </div>
    </div>
    <div id="status-dialog-content" class="popup">
        <h2>Update Status</h2>
        <button
            data-fancybox-close=""
            @click="closeModal()"
            class="f-button is-close-btn"
            title="Close"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                tabindex="-1"
            >
                <path d="M20 20L4 4m16 0L4 20"></path>
            </svg>
        </button>
        <div class="card card-3 mt-2">
            <vee-form
                :validation-schema="schema"
                @submit="updateStatus"
                ref="cancelForm"
            >
                <div class="row">
                    <div class="col-12">
                        <div class="form-field">
                            <label for="">Select Status<span>*</span></label>
                            <vee-field
                                v-slot="{ field, errors }"
                                class="form-control"
                                name="status"
                                as="select"
                                v-model="updatedStatus"
                                @change="changeStatus()"
                            >
                                <option value="">Select Status</option>
                                <option
                                    v-for="(status, index) in pendingStatuses"
                                    :key="index"
                                    :value="status.value"
                                    v-if="
                                        type == 'pending' ||
                                        (type == 'referral' &&
                                            statusAppointment.status !=
                                                'Cancelled' &&
                                            statusAppointment.status ==
                                                'Pending' &&
                                            $dayjs(
                                                statusAppointment.date
                                            ).isAfter($dayjs(currentDate)))
                                    "
                                >
                                    {{ status.text }}
                                </option>
                                <option
                                    v-for="(status, index) in confirmedStatuses"
                                    :key="index"
                                    :value="status.value"
                                    v-if="
                                        type == 'confirmed' ||
                                        (type == 'referral' &&
                                            statusAppointment.status !=
                                                'Cancelled' &&
                                            statusAppointment.status !=
                                                'Pending' &&
                                            $dayjs(
                                                statusAppointment.date
                                            ).isAfter($dayjs(currentDate)))
                                    "
                                >
                                    {{ status.text }}
                                </option>
                            </vee-field>
                            <div
                                class="error invalid-feedback"
                                v-if="statusErr"
                            >
                                <p class="invalid-feedback">
                                    This field is required.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="col-12"
                        v-if="displayReason && !displayPaymentMode"
                    >
                        <div class="form-field">
                            <vee-field
                                v-slot="{ field, errors }"
                                class="form-control"
                                name="reason"
                                type="text"
                                v-model="cancellationReason"
                            >
                                <label for="">Reason<span>*</span></label>
                                <textarea
                                    name="reason"
                                    v-bind="field"
                                    id=""
                                    cols="30"
                                    rows="6"
                                    class="form-control h-auto"
                                    placeholder="Enter Reason"
                                ></textarea>
                                <div
                                    class="error invalid-feedback"
                                    v-if="errors[0]"
                                >
                                    {{ errors[0] }}
                                </div>
                            </vee-field>
                        </div>
                    </div>
                    <div
                        class="col-12"
                        v-if="displayPaymentMode && !displayReason"
                    >
                        <div class="form-field">
                            <label for=""
                                >Select Payment Mode<span>*</span></label
                            >
                            <vee-field
                                v-slot="{ field, errors }"
                                class="form-control"
                                name="payment_mode"
                                as="select"
                                v-model="payment_mode"
                            >
                                <option value="">Select Mode</option>
                                <option
                                    v-for="(status, index) in paymentMode"
                                    :key="index"
                                    :value="status.value"
                                >
                                    {{ status.text }}
                                </option>
                            </vee-field>
                            <div class="error invalid-feedback">
                                <ErrorMessage
                                    class="invalid-feedback"
                                    name="payment_mode"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="submit-wrap">
                            <button
                                type="submit"
                                class="btn btn-primary-light btn-medium min-small"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    style="margin: 0 10px"
                                    width="24px"
                                    height="24px"
                                    viewBox="0 0 100 100"
                                    preserveAspectRatio="xMidYMid"
                                    v-if="submittingForm"
                                >
                                    <circle
                                        cx="50"
                                        cy="50"
                                        r="32"
                                        stroke-width="8"
                                        stroke="#3A89C9"
                                        stroke-dasharray="50.26548245743669 50.26548245743669"
                                        fill="none"
                                        stroke-linecap="round"
                                    >
                                        <animateTransform
                                            attributeName="transform"
                                            type="rotate"
                                            repeatCount="indefinite"
                                            dur="1s"
                                            keyTimes="0;1"
                                            values="0 50 50;360 50 50"
                                        ></animateTransform>
                                    </circle>
                                </svg>
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </vee-form>
        </div>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { useToast } from "vue-toast-notification";
import "vue-toast-notification/dist/theme-sugar.css";
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";
import {
    fetch,
    getSlots,
    rescheduleAppointment,
    updateAppointment,
} from "@/api/appointment";
const date = new Date();

let day = date.getDate();
let month = date.getMonth() + 1;
let year = date.getFullYear();
let currentDate = `${year}-${month}-${day}`;
export default {
    props: {
        appointments: Array,
        totalCount: Number,
        type: String,
    },
    components: { flatPickr },
    data() {
        return {
            statusAppointment: {},
            submittingForm: false,
            currentDate: currentDate,
            displayReason: false,
            displayPaymentMode: false,
            updatedStatus: "",
            payment_mode: null,
            appointmentId: null,
            cancellationReason: null,
            appointment: {},
            date: null,
            slots: [],
            displayOverlay: false,
            schema: {
                reason: "",
                payment_mode: "",
            },
            config: {
                altInput: true,
                altFormat: "j F, Y",
                dateFormat: "Y-m-d",
                minDate: currentDate,
                disableMobile: true,
            },
            noSlots: false,
            pendingStatuses: [
                {
                    type: "pending",
                    text: "Booked",
                    value: "booked",
                },
                {
                    type: "",
                    text: "Cancel",
                    value: "cancelled",
                },
            ],
            confirmedStatuses: [
                {
                    type: "",
                    text: "Cancel",
                    value: "cancelled",
                },
                {
                    text: "Fulfilled",
                    value: "fulfilled",
                },
                {
                    text: "Arrived",
                    value: "arrived",
                },
            ],
            paymentMode: [
                {
                    text: "Cash",
                    value: "cash",
                },
                {
                    text: "Credit/Debit Card",
                    value: "credit/debit card",
                },
                {
                    text: "Cheque",
                    value: "cheque",
                },
            ],
            statusErr: false,
        };
    },
    methods: {
        changePage(pageNo) {
            this.$emit("changePage", (pageNo - 1) * 10);
        },
        getAppointmentInfo(id) {
            fetch(id).then((response) => {
                if (response.success === true) {
                    this.appointment = response.data;
                }
            });
        },
        getClinicianSlots() {
            if (this.date != null) {
                this.displayOverlay = true;
                this.noSlots = false;
                const query = `?date=${this.date}&location=${this.appointment.location}&clinician=${this.appointment.clinician.azalea_id}`;
                getSlots(query).then((response) => {
                    if (response.success === true) {
                        const data = response.data;
                        if (Object.keys(data).length) {
                            const date = Object.keys(response.data)[0];
                            this.slots = response.data[date];
                            this.displayOverlay = false;
                        } else {
                            this.slots = [];
                            this.noSlots = true;
                            this.displayOverlay = false;
                        }
                    }
                });
            }
        },
        reschedule(data, appointmentId) {
            this.displayOverlay = true;
            const payload = {
                appointment_id: appointmentId,
                appointmentType: data.appointmentType,
                end_date_time: data.end_date_time,
                start_date_time: data.start_date_time,
                time: data.start_time,
                date: data.formatted_date,
            };
            rescheduleAppointment(payload).then((response) => {
                this.displayOverlay = false;
                const $toast = useToast();
                $toast.open({
                    message: response.message,
                    type: response.success === true ? "success" : "error",
                    position: "top-right",
                });
                if (response.success === true) {
                    this.closeModal();
                    this.$emit("changePage", 0);
                }
            });
        },
        changeStatus() {
            this.cancellationReason = null;
            this.displayReason = false;
            this.displayPaymentMode = false;
            if (this.updatedStatus == "cancelled") {
                this.displayReason = true;
                this.schema.reason = "required";
            } else if (this.updatedStatus == "fulfilled") {
                this.displayPaymentMode = true;
                this.schema.payment_mode = "required";
            } else {
                this.displayReason = false;
                this.schema.reason = "";
            }
        },
        updateStatus(values, { resetForm }) {
            this.statusErr = false;
            if (this.updatedStatus == "") {
                this.statusErr = true;
            } else {
                let data = new FormData();
                this.submittingForm = true;
                data.append("appointment_id", this.appointmentId);
                data.append("cancellation_reason", this.cancellationReason);
                data.append("status", this.updatedStatus);
                data.append("payment_mode", this.payment_mode);
                updateAppointment(data)
                    .then((response) => {
                        this.handleResponse(response);
                        this.submittingForm = false;
                        if (response.success == true) {
                            resetForm();
                            this.closeModal();
                            this.$emit("changePage", 0);
                        }
                    })
                    .catch((error) => {
                        this.submittingForm = false;
                        this.handleCatch(error);
                    });
            }
        },
        closeModal() {
            this.noSlots = false;
            this.slots = [];
            this.date = null;
            this.appointmentId = null;
            this.cancellationReason = null;
            this.displayReason = false;
            this.updatedStatus = "";
            this.displayPaymentMode = false;
            this.statusErr = false;
            Fancybox.close();
        },
    },
};

Fancybox.bind("[data-fancybox]", {
    closeButton: false,
    backdropClick: false,
});
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
