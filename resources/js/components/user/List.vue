<template>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th width="45px"></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="250">Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody v-if="users.length">
                <tr v-for="(user, index) in users" :key="index">
                    <td>
                        <input
                            class="form-check-input"
                            :value="user.id"
                            v-model="selectedUsers"
                            type="checkbox"
                        />
                    </td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role ? user.role.name : "intake" }}</td>
                    <td>
                        <span
                            @click="
                                updateStatus(user, user.status === 1 ? 0 : 1)
                            "
                            :class="
                                user.status == 1 ? 'status active' : 'status'
                            "
                        ></span>
                    </td>
                    <td>
                        <ul class="actions">
                            <li>
                                <button
                                    class="btn btn-link"
                                    @click="editRecord(user.id)"
                                >
                                    <i class="icon-edit"></i>Edit
                                </button>
                            </li>
                            <li>
                                <button
                                    class="btn"
                                    @click="deleteRecord(user.id)"
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
                :disabled="selectedUsers.length ? false : true"
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
                            <!-- <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li> -->
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
import { deleteRecord, deleteMultiple, update } from "@/api/user";
export default {
    props: {
        users: Array,
        totalCount: Number,
    },
    data() {
        return {
            selectedUsers: [],
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
            const user_ids = this.selectedUsers;
            this.$swal({
                title: "Are you sure ?",
                showCancelButton: true,
                icon: "",
                confirmButtonText: "Continue",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteMultiple({ user_ids }).then(() => {
                        this.$emit("changePage", 0);
                        this.selectedUsers = [];
                    });
                }
            });
        },
        updateStatus(userData, status) {
            userData.status = status;
            update(userData, userData.id)
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
