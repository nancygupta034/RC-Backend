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
                    <th>Date</th>
                    <th>Status</th>
                    <th width="150">Action</th>
                </tr>
            </thead>
            <tbody v-if="news.length">
                <tr v-for="(news, index) in news" :key="index">
                    <td>
                        <input
                            class="form-check-input"
                            :value="news.id"
                            v-model="selectedNews"
                            type="checkbox"
                        />
                    </td>
                    <td>
                        <div class="row flex-nowrap align-items-center">
                            <div class="col-auto">
                                <div class="user-img">
                                    <img
                                        :src="`${news.image}`"
                                        alt=""
                                        v-if="news.image"
                                    />
                                    <img
                                        src="/assets/images/product-placeholder.png"
                                        v-else
                                    />
                                </div>
                            </div>
                            <div class="col">
                                <div class="user-info">
                                    {{ news.title }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row flex-nowrap align-items-center">
                            {{ formatDate(news.date) }}
                        </div>
                    </td>
                    <td>
                        <span
                            @click="
                                updateStatus(news, news.status === 1 ? 0 : 1)
                            "
                            :class="
                                news.status == 1 ? 'status active' : 'status'
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
                                            `/news/edit/${news.id}`,
                                            news.id
                                        )
                                    "
                                >
                                    <i class="icon-edit"></i>Edit
                                </button>
                            </li>
                            <li>
                                <button
                                    class="btn"
                                    @click="deleteRecord(news.id)"
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
                :disabled="selectedNews.length ? false : true"
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
import { deleteRecord, deleteMultiple, updateStatus } from "@/api/news";
export default {
    props: {
        news: Array,
        totalCount: Number,
    },
    data() {
        return {
            selectedNews: [],
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
            const news_ids = this.selectedNews;
            this.$swal({
                title: "Are you sure ?",
                showCancelButton: true,
                icon: "",
                confirmButtonText: "Continue",
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteMultiple({ news_ids }).then(() => {
                        this.$emit("changePage", 0);
                        this.selectedNews = [];
                    });
                }
            });
        },
        updateStatus(newsData, status) {
            newsData.status = status;
            updateStatus(newsData, newsData.id)
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
