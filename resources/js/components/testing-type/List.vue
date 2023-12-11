<template>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th width="45px">
                        <!-- <input class="form-check-input" type="checkbox" value=""> -->
                    </th>
                    <th>Name</th>
                    <th width="250">Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(type, index) in types">
                    <td>
                        <input
                            class="form-check-input"
                            :value="type.id"
                            v-model="selectedTypes"
                            type="checkbox"
                        />
                    </td>
                    <td>{{ type.name }}</td>
                    <td>
                        <span
                            @click="
                                updateStatus(type, type.status === 1 ? 0 : 1)
                            "
                            :class="
                                type.status == 1 ? 'status active' : 'status'
                            "
                        ></span>
                    </td>

                    <td>
                        <ul class="actions">
                            <li>
                                <button
                                    class="btn btn-link"
                                    @click="editRecord(type.id)"
                                >
                                    <i class="icon-edit"></i>Edit
                                </button>
                            </li>
                            <li>
                                <button
                                    class="btn"
                                    @click="deleteRecord(type.id)"
                                >
                                    <i class="icon-delete"></i>Delete
                                </button>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row gy-4 align-items-center">
        <div class="col-auto">
            <button
                class="btn btn-danger py-2"
                @click="deleteData"
                :disabled="selectedTypes.length ? false : true"
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
import {
    deleteTestingType,
    deleteMultipleTestingType,
    updateTestingType,
} from "@/api/testingType";
export default {
    props: {
        types: Array,
        totalCount: Number,
    },
    data() {
        return {
            selectedTypes: [],
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
                    deleteTestingType(id).then(() => {
                        this.$emit("changePage", 0);
                    });
                }
            });
        },
        deleteData() {
            const types = this.selectedTypes;
            this.$swal({
                title: "Are you sure ?",
                showCancelButton: true,
                icon: "",
                confirmButtonText: "Continue",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteMultipleTestingType({ types }).then(() => {
                        this.$emit("changePage", 0);
                        this.selectedTypes = [];
                    });
                }
            });
        },
        updateStatus(data, status) {
            data.status = status;
            updateTestingType(data, data.id)
                .then(() => {
                    this.$emit("changePage", 0);
                })
                .catch((error) => {
                    if (error.response?.data) {
                        console.log(response.message);
                    }
                });
        },
    },
};
</script>
