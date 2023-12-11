<template>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th width="45px"></th>
                    <th>Title</th>
                    <th width="250">Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody v-if="mentalHealth.length">
                <tr v-for="(blog, index) in mentalHealth" :key="index">
                    <td>
                        <input
                            class="form-check-input"
                            :value="blog.id"
                            v-model="selectedMenatlHealth"
                            type="checkbox"
                        />
                    </td>
                    <td>
                        <div class="row flex-nowrap align-items-center">
                            <div class="col">
                                <div class="user-info">
                                    {{ blog.title }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span
                            @click="
                                updateStatus(blog, blog.status === 1 ? 0 : 1)
                            "
                            :class="
                                blog.status == 1 ? 'status active' : 'status'
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
                                            `/mental-health/edit/${blog.id}`,
                                            blog.id
                                        )
                                    "
                                >
                                    <i class="icon-edit"></i>Edit
                                </button>
                            </li>
                            <li>
                                <button
                                    class="btn"
                                    @click="deleteRecord(blog.id)"
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
                :disabled="selectedMenatlHealth.length ? false : true"
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
    deleteRecord,
    deleteMultiple,
    update,
    updateStatus,
} from "@/api/mentalHealth";
export default {
    props: {
        mentalHealth: Array,
        totalCount: Number,
    },
    data() {
        return {
            selectedMenatlHealth: [],
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
            const mental_health_ids = this.selectedMenatlHealth;
            this.$swal({
                title: "Are you sure ?",
                showCancelButton: true,
                icon: "",
                confirmButtonText: "Continue",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteMultiple({ mental_health_ids }).then(() => {
                        this.$emit("changePage", 0);
                        this.selectedMenatlHealth = [];
                    });
                }
            });
        },
        updateStatus(blogData, status) {
            blogData.status = status;
            updateStatus(blogData, blogData.id)
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
