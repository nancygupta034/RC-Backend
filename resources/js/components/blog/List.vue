<template>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th width="45px">
                        <!-- <input
                            class="form-check-input"
                            type="checkbox"
                            value=""
                        /> -->
                    </th>
                    <th>Title</th>
                    <th>Author's Name</th>
                    <th>Published On</th>
                    <th width="250">Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody v-if="blogs.length">
                <tr v-for="(blog, index) in blogs" :key="index">
                    <td>
                        <input
                            class="form-check-input"
                            :value="blog.id"
                            v-model="selectedServices"
                            type="checkbox"
                        />
                    </td>
                    <td>
                        <div class="row flex-nowrap align-items-center">
                            <div class="col-auto">
                                <div class="user-img">
                                    <img
                                        :src="`${blog.image}`"
                                        alt=""
                                        v-if="blog.image"
                                    />
                                    <img
                                        src="/assets/images/product-placeholder.png"
                                        v-else
                                    />
                                </div>
                            </div>
                            <div class="col">
                                <div class="user-info">
                                    {{ blog.title }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{ blog.author_name }}</td>
                    <td>{{ formatDate(blog.created_at) }}</td>
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
                                            `/blogs/edit/${blog.id}`,
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
                :disabled="selectedServices.length ? false : true"
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
import { deleteRecord, deleteMultiple, update, updateStatus } from "@/api/blog";
export default {
    props: {
        blogs: Array,
        totalCount: Number,
    },
    data() {
        return {
            selectedServices: [],
        };
    },
    methods: {
        formatDate(date) {
            const year = new Date(date).getFullYear();
            const month = new Date(date).getMonth();
            const day = new Date(date).getDate();
            return `${month + 1}/${day}/${year}`;
        },
        changePage(pageNo) {
            this.$emit("changePage", (pageNo - 1) * 10);
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
            const blog_ids = this.selectedServices;
            this.$swal({
                title: "Are you sure ?",
                showCancelButton: true,
                icon: "",
                confirmButtonText: "Continue",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteMultiple({ blog_ids }).then(() => {
                        this.$emit("changePage", 0);
                        this.selectedServices = [];
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
