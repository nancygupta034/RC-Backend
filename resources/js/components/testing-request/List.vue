<template>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile No.</th>
                    <th>Testing Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody v-if="testingRequests.length">
                <tr v-for="(request, index) in testingRequests" :key="index">
                    <td>{{ request.first_name }} {{ request.last_name }}</td>
                    <td>{{ request.email }}</td>
                    <td>{{ request.mobile_number }}</td>
                    <td>
                        <div class="row flex-nowrap align-items-center">
                            <div class="col">
                                <div class="user-info">
                                    {{
                                        request.testing_type
                                            ? request.testing_type.name
                                            : "N/A"
                                    }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <ul class="actions">
                            <li>
                                <router-link
                                    :to="`/testing-requests/${request.id}`"
                                    class="btn btn-link"
                                >
                                    <i class="icon-edit"></i>View
                                </router-link>
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
</template>

<script>
import {} from "@/api/testingRequest";
export default {
    props: {
        testingRequests: Array,
        totalCount: Number,
    },
};
</script>
