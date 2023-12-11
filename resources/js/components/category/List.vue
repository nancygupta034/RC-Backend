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
            <tbody v-if="blogCategories.length">
                <tr
                    v-for="(blogCategory, index) in blogCategories"
                    :key="index"
                >
                    <td>
                        <input
                            class="form-check-input"
                            :value="blogCategory.id"
                            v-model="selectedBlogCategories"
                            type="checkbox"
                        />
                    </td>
                    <td>{{ blogCategory.name }}</td>
                    <td>
                        <span
                            @click="
                                updateStatus(
                                    blogCategory,
                                    blogCategory.status === 1 ? 0 : 1
                                )
                            "
                            :class="
                                blogCategory.status == 1
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
                                    @click="editRecord(blogCategory.id)"
                                >
                                    <i class="icon-edit"></i>Edit
                                </button>
                            </li>
                            <li>
                                <button
                                    class="btn"
                                    @click="deleteRecord(blogCategory.id)"
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
                    <td colspan="4" class="text-center">No record found</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row gy-4 align-items-center">
        <div class="col-auto">
            <button
                class="btn btn-danger py-2"
                @click="deleteData"
                :disabled="selectedBlogCategories.length ? false : true"
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
    deleteCategories,
    deleteMultipleCategories,
    updateCategories,
} from "@/api/category";
export default {
    props: {
        blogCategories: Array,
        totalCount: Number,
    },
    data() {
        return {
            selectedBlogCategories: [],
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
                    deleteCategories(id).then(() => {
                        this.$emit("changePage", 0);
                    });
                }
            });
        },
        deleteData() {
            const blogCategories = this.selectedBlogCategories;
            this.$swal({
                title: "Are you sure ?",
                showCancelButton: true,
                icon: "",
                confirmButtonText: "Continue",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteMultipleCategories({
                        categories: blogCategories,
                    }).then(() => {
                        this.$emit("changePage", 0);
                        this.selectedBlogCategories = [];
                    });
                }
            });
        },
        updateStatus(data, status) {
            data.status = status;
            updateCategories(data, data.id)
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
