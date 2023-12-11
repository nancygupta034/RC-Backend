<template>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th width="45px"></th>
                    <th>Clinician Info</th>
                    <th>Email</th>
                    <th>Azalea ID</th>
                    <th>Tru Billing ID</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody v-if="clinicians.length">
                <tr v-for="(clinician, index) in clinicians" :key="index">
                    <td>
                        <input
                            class="form-check-input"
                            :value="clinician.id"
                            v-model="selectedClinicians"
                            type="checkbox"
                        />
                    </td>
                    <td>
                        <div class="row flex-nowrap align-items-center">
                            <div class="col-auto">
                                <div class="user-img">
                                    <!-- <img
                                        :src="`/public${clinician.image}`"
                                        :alt="clinician.name"
                                        v-if="clinician.image"
                                    /> -->
                                    <img
                                        :src="`${clinician.image}`"
                                        :alt="clinician.name"
                                        v-if="clinician.image"
                                    />
                                    <img
                                        alt="sdfdfgfgdfasdf"
                                        src="/assets/images/user-placeholder.jpeg"
                                        v-else
                                    />
                                </div>
                            </div>
                            <div class="col">
                                <div class="user-info">
                                    <strong>{{ clinician.name }}</strong>
                                    <small>{{ clinician.qualification }}</small>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{ clinician.email }}</td>
                    <td>{{ clinician.azalea_id }}</td>
                    <td>{{ clinician.tru_billing_id }}</td>
                    <td>
                        <span
                            @click="
                                updateStatus(
                                    clinician,
                                    clinician.status === 1 ? 0 : 1
                                )
                            "
                            :class="
                                clinician.status == 1
                                    ? 'status active'
                                    : 'status'
                            "
                        ></span>
                    </td>
                    <td>
                        <ul class="actions">
                            <li>
                                <button
                                    class="btn btn-link"
                                    @click="
                                        goToEdit(
                                            `/clinicians/edit/${clinician.id}`
                                        )
                                    "
                                >
                                    <i class="icon-edit"></i>Edit
                                </button>
                            </li>
                            <li>
                                <button
                                    class="btn"
                                    @click="deleteRecord(clinician.id)"
                                >
                                    <i class="icon-delete"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center">No record found</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row gy-4 align-items-center">
        <div class="col-auto">
            <button
                class="btn btn-danger py-2"
                @click="deleteData"
                :disabled="selectedClinicians.length ? false : true"
            >
                Delete Selected
            </button>
        </div>
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
</template>

<script>
import { deleteRecord, deleteMultiple, updateStatus } from "@/api/clinician";
export default {
    props: {
        clinicians: Array,
        totalCount: Number,
    },
    data() {
        return {
            selectedClinicians: [],
        };
    },
    methods: {
        changePage(pageNo) {
            this.$emit("changePage", (pageNo - 1) * 10);
        },
        editRecord(id) {
            this.$emit("getRecord", id);
        },
        deleteRecord(id) {
            this.$swal({
                title: "Are you sure ?",
                showCancelButton: true,
                icon: "",
                confirmButtonText: "Continue",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteRecord(id).then(() => {
                        this.$emit("changePage", 0);
                    });
                }
            });
        },
        deleteData() {
            const clinicians = this.selectedClinicians;
            this.$swal({
                title: "Are you sure ?",
                showCancelButton: true,
                icon: "",
                confirmButtonText: "Continue",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteMultiple({ clinicians }).then(() => {
                        this.$emit("changePage", 0);
                        this.selectedClinicians = [];
                    });
                }
            });
        },
        updateStatus(data, status) {
            data.status = status;
            updateStatus(data, data.id)
                .then(() => {})
                .catch((error) => {
                    if (error.response?.data) {
                        console.log(response.message);
                    }
                });
        },
    },
};
</script>
