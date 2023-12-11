<template>
    <aside class="bg-primary-light sidebar col-auto me-3 p-4">
        <div class="logo">
            <a href="/dashboard">
                <img src="../../assets/images/logo.png" alt="" />
            </a>
        </div>
        <div class="user-login-details">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="icon-avatar"></i>
                </div>
                <div class="col">
                    <div class="user-login-info">
                        <h2>Welcome {{ authUserName }}</h2>
                        <ul class="link-list">
                            <li>
                                <a href="/change-password">Change Password</a>
                            </li>
                            <li><btn @click="handleLogout">Logout</btn></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <ul>
                <li
                    :class="currentPath.includes('dashboard') ? 'active' : ''"
                    v-if="authUserRole !== 'CMS'"
                >
                    <a href="/dashboard"
                        ><i class="bi bi-speedometer"></i
                        ><span>Dashboard</span></a
                    >
                </li>
                <li
                    :class="
                        currentPath.includes('testing-requests') ? 'active' : ''
                    "
                    v-if="authUserRole !== 'CMS'"
                >
                    <a href="/testing-requests"
                        ><i class="bi bi-clipboard-check"></i
                        ><span>Testing Requests</span></a
                    >
                </li>
                <li
                    :class="appointmentClass"
                    class="has-dropdown"
                    @click="displayMenu('appointments')"
                    v-if="authUserRole !== 'CMS'"
                >
                    <a href="javascript:void(0)"
                        ><i class="bi bi-calendar2-week"></i
                        ><span>Appointments</span></a
                    >
                    <transition-expand :duration="{ enter: 200, leave: 500 }">
                        <div v-if="isAppointmentVisible" class="submenu">
                            <ul>
                                <li
                                    @click.stop="
                                        changeRoute('/confirmed-appointments')
                                    "
                                    :class="
                                        currentPath.includes(
                                            'confirmed-appointments'
                                        )
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/confirmed-appointments"
                                        >Confirmed Appointments</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="
                                        changeRoute('/pending-appointments')
                                    "
                                    :class="
                                        currentPath.includes(
                                            'pending-appointments'
                                        )
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/pending-appointments"
                                        >Pending Appointments</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="
                                        changeRoute('/referral-appointments')
                                    "
                                    :class="
                                        currentPath.includes(
                                            'referral-appointments'
                                        )
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/referral-appointments"
                                        >Referral Appointments</router-link
                                    >
                                </li>
                            </ul>
                        </div>
                    </transition-expand>
                </li>
                <li
                    :class="masterClass"
                    class="has-dropdown"
                    @click="displayMenu('masters')"
                    v-if="authUserRole == 'Admin'"
                >
                    <a href="javascript:void(0)">
                        <i class="icon-settings"></i><span>Masters</span>
                    </a>
                    <transition-expand :duration="{ enter: 200, leave: 500 }">
                        <div
                            v-if="isMasterVisible"
                            class="submenu"
                            id="masters-menu"
                        >
                            <ul>
                                <li
                                    @click.stop="
                                        changeRoute('/masters/specialities')
                                    "
                                    :class="
                                        currentPath.includes('specialities')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/masters/specialities"
                                        >Specialities</router-link
                                    >
                                </li>

                                <li
                                    @click.stop="
                                        changeRoute('/masters/testing-types')
                                    "
                                    :class="
                                        currentPath.includes('testing-types')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/masters/testing-types"
                                        >Testing Types</router-link
                                    >
                                </li>
                                <!-- <li
                                    @click.stop="
                                        changeRoute(
                                            '/admin/masters/age-ranges'
                                        )
                                    "
                                    :class="
                                        currentPath.includes('age-ranges')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link
                                        to="/admin/masters/age-ranges"
                                        >Age Ranges</router-link
                                    >
                                </li> -->
                                <li
                                    @click.stop="
                                        changeRoute(
                                            '/masters/services-provided'
                                        )
                                    "
                                    :class="
                                        currentPath.includes(
                                            'services-provided'
                                        )
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/masters/services-provided"
                                        >Services Provided</router-link
                                    >
                                </li>
                                <li @click="currentPath = '/masters/states-covered'" :class="currentPath == '/masters/states-covered' ? 'active' : ''"><router-link to="/masters/states-covered">States Covered</router-link></li>
                                <li
                                    @click.stop="changeRoute('/masters/users')"
                                    :class="
                                        currentPath.includes('users')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/masters/users"
                                        >Users</router-link
                                    >
                                </li>
                            </ul>
                        </div>
                    </transition-expand>
                </li>
                <li
                    :class="clinicianClass"
                    class="has-dropdown"
                    @click="displayMenu('clinicians')"
                    v-if="authUserRole == 'Admin'"
                >
                    <a href="javascript:void(0)"
                        ><i class="icon-user"></i><span>Our Clinicians</span></a
                    >
                    <transition-expand :duration="{ enter: 200, leave: 500 }">
                        <div v-if="isClinicianVisible" class="submenu">
                            <ul>
                                <li
                                    @click.stop="changeRoute('/clinicians/add')"
                                    :class="
                                        currentPath.includes('clinicians/add')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/clinicians/add"
                                        >Add New</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="
                                        changeRoute('/clinicians/list')
                                    "
                                    :class="
                                        currentPath.includes('clinicians/list')
                                            ? 'active'
                                            : '' ||
                                              currentPath.includes(
                                                  'clinicians/edit'
                                              )
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/clinicians/list"
                                        >List/Edit</router-link
                                    >
                                </li>
                            </ul>
                        </div>
                    </transition-expand>
                </li>
                <li
                    :class="cmsClass"
                    class="has-dropdown"
                    @click="displayMenu('cms')"
                    v-if="authUserRole !== 'Intake'"
                >
                    <a href="javascript:void(0)"
                        ><i class="bi bi-grid"></i><span>CMS</span></a
                    >
                    <transition-expand :duration="{ enter: 200, leave: 500 }">
                        <div v-if="isCMSVisible" class="submenu">
                            <ul>
                                <li
                                    @click.stop="
                                        changeRoute('/cms/our-story/data')
                                    "
                                    :class="
                                        currentPath.includes('our-story')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/cms/our-story/data"
                                        >Our Story</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="
                                        changeRoute('/cms/opportunity/data')
                                    "
                                    :class="
                                        currentPath.includes('opportunity')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/cms/opportunity/data"
                                        >Opportunity</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="
                                        changeRoute('/cms/referral/data')
                                    "
                                    :class="
                                        currentPath.includes('referral/data')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/cms/referral/data"
                                        >Referral</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="
                                        changeRoute('/cms/appointment/data')
                                    "
                                    :class="
                                        currentPath.includes('appointment/data')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/cms/appointment/data"
                                        >Appointment's T&C</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="
                                        changeRoute('/cms/psych-testing/data')
                                    "
                                    :class="
                                        currentPath.includes('psych-testing/data')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/cms/psych-testing/data"
                                        >Psych Testing's T&C</router-link
                                    >
                                </li>
                            </ul>
                        </div>
                    </transition-expand>
                </li>
                <li
                    :class="blogsClass"
                    class="has-dropdown"
                    @click="displayMenu('blogs')"
                    v-if="authUserRole !== 'Intake'"
                >
                    <a href="javascript:void(0)"
                        ><i class="bi bi-grid"></i><span>Blogs</span></a
                    >
                    <transition-expand :duration="{ enter: 200, leave: 500 }">
                        <div v-if="isBlogVisible" class="submenu">
                            <ul>
                                <li
                                    @click.stop="
                                        changeRoute('/blog-categories')
                                    "
                                    :class="
                                        currentPath.includes('blog-categories')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/blog-categories"
                                        >Categories</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="changeRoute('/blogs')"
                                    :class="
                                        currentPath.includes('blogs')
                                            ? 'active'
                                            : '' ||
                                              currentPath.includes('blogs')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/blogs/list">Blogs</router-link>
                                </li>
                            </ul>
                        </div>
                    </transition-expand>
                </li>
                <li
                    :class="mentalHealthClass"
                    class="has-dropdown"
                    @click="displayMenu('mental-health')"
                    v-if="authUserRole !== 'Intake'"
                >
                    <a href="javascript:void(0)"
                        ><i class="bi bi-bullseye"></i
                        ><span>Mental Health</span></a
                    >
                    <transition-expand :duration="{ enter: 200, leave: 500 }">
                        <div v-if="isMentalHealthVisible" class="submenu">
                            <ul>
                                <li
                                    @click.stop="
                                        changeRoute('/mental-health-categories')
                                    "
                                    :class="
                                        currentPath.includes(
                                            'mental-health-categories'
                                        )
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/mental-health-categories"
                                        >Categories</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="changeRoute('/mental-health')"
                                    :class="
                                        currentPath.includes('mental-health') &&
                                        !currentPath.includes(
                                            'mental-health-categories'
                                        )
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/mental-health/list"
                                        >Articles</router-link
                                    >
                                </li>
                            </ul>
                        </div>
                    </transition-expand>
                </li>
                <li
                    :class="newsClass"
                    class="has-dropdown"
                    @click="displayMenu('news')"
                    v-if="authUserRole !== 'Intake'"
                >
                    <a href="javascript:void(0)"
                        ><i class="bi bi-newspaper"></i
                        ><span>News/Events</span></a
                    >
                    <transition-expand :duration="{ enter: 200, leave: 500 }">
                        <div v-if="isNewsVisible" class="submenu">
                            <ul>
                                <li
                                    @click.stop="
                                        changeRoute('/news-categories')
                                    "
                                    :class="
                                        currentPath.includes('news-categories')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/news-categories"
                                        >Categories</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="changeRoute('/news')"
                                    :class="
                                        currentPath.includes('news') &&
                                        !currentPath.includes('news-categories')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/news/list">News</router-link>
                                </li>
                            </ul>
                        </div>
                    </transition-expand>
                </li>
                <li
                    :class="faqsClass"
                    class="has-dropdown"
                    @click="displayMenu('faqs')"
                    v-if="authUserRole !== 'Intake'"
                >
                    <a href="javascript:void(0)"
                        ><i class="bi bi-question-octagon"></i
                        ><span>FAQ</span></a
                    >
                    <transition-expand :duration="{ enter: 200, leave: 500 }">
                        <div v-if="isFaqsVisible" class="submenu">
                            <ul>
                                <li
                                    @click.stop="
                                        changeRoute('/faqs-categories')
                                    "
                                    :class="
                                        currentPath.includes('faqs-categories')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/faqs-categories"
                                        >Categories</router-link
                                    >
                                </li>
                                <li
                                    @click.stop="changeRoute('/faqs')"
                                    :class="
                                        currentPath.includes('faqs') &&
                                        !currentPath.includes('faqs-categories')
                                            ? 'active'
                                            : ''
                                    "
                                >
                                    <router-link to="/faqs/list">FAQ</router-link>
                                </li>
                            </ul>
                        </div>
                    </transition-expand>
                </li>
                <li
                    :class="currentPath.includes('services') ? 'active' : ''"
                    v-if="authUserRole !== 'Intake'"
                >
                    <a href="/services/list"
                        ><i class="icon-dashboard"></i><span>Services</span></a
                    >
                </li>
                <li
                    :class="
                        currentPath.includes('team-members') ? 'active' : ''
                    "
                    v-if="authUserRole !== 'Intake'"
                >
                    <a href="/team-members"
                        ><i class="bi bi-person-plus"></i><span>Team</span></a
                    >
                </li>
            </ul>
        </nav>
    </aside>
</template>
<script>
import store from "../../store";
import { TransitionExpand } from "@morev/vue-transitions";
export default {
    name: "AdminNavbar",
    components: { TransitionExpand },
    data() {
        return {
            currentPath: this.$route.path,
            masterClass: "",
            clinicianClass: "",
            appointmentClass: "",
            cmsClass: "",
            faqsClass: "",
            blogsClass: "",
            mentalHealthClass: "",
            serviceClass: "",
            newsClass: "",
            isMasterVisible: false,
            isAppointmentVisible: false,
            isClinicianVisible: false,
            isCMSVisible: false,
            isBlogVisible: false,
            isServiceVisible: false,
            isNewsVisible: false,
            isFaqsVisible: false,
            isMentalHealthVisible: false,
            authUserRole: "Admin",
            authUserName: "Admin",
        };
    },
    methods: {
        displayMenu(menuName) {
            if (menuName === "masters") {
                this.isCMSVisible = false;
                this.cmsClass = "";
                this.isMasterVisible = !this.isMasterVisible;
                this.isClinicianVisible = false;
                this.isBlogVisible = false;
                this.isFaqsVisible = false;
                this.isNewsVisible = false;
                this.isAppointmentVisible = false;
                this.isMentalHealthVisible = false;
                this.masterClass =
                    this.masterClass === "active" ? "" : "active";
                this.clinicianClass = "";
                this.faqsClass = "";
                this.appointmentClass = "";
                this.blogsClass = "";
                this.newsClass = "";
                this.mentalHealthClass = "";
            }

            if (menuName === "appointments") {
                this.isCMSVisible = false;
                this.cmsClass = "";
                this.isAppointmentVisible = !this.isAppointmentVisible;
                this.isMasterVisible = false;
                this.isClinicianVisible = false;
                this.isBlogVisible = false;
                this.isFaqsVisible = false;
                this.isNewsVisible = false;
                this.isMentalHealthVisible = false;
                this.appointmentClass =
                    this.appointmentClass === "active" ? "" : "active";
                this.masterClass = "";
                this.clinicianClass = "";
                this.faqsClass = "";
                this.blogsClass = "";
                this.newsClass = "";
                this.mentalHealthClass = "";
            }

            if (menuName === "clinicians") {
                this.isCMSVisible = false;
                this.cmsClass = "";
                this.isClinicianVisible = !this.isClinicianVisible;
                this.isMasterVisible = false;
                this.isBlogVisible = false;
                this.isFaqsVisible = false;
                this.isNewsVisible = false;
                this.isAppointmentVisible = false;
                this.isMentalHealthVisible = false;
                this.clinicianClass =
                    this.clinicianClass === "active" ? "" : "active";
                this.masterClass = "";
                this.faqsClass = "";
                this.appointmentClass = "";
                this.blogsClass = "";
                this.newsClass = "";
                this.mentalHealthClass = "";
            }

            if (menuName === "cms") {
                this.isCMSVisible = !this.isCMSVisible;
                this.isMasterVisible = false;
                this.isClinicianVisible = false;
                this.isBlogVisible = false;
                this.isFaqsVisible = false;
                this.isNewsVisible = false;
                this.isAppointmentVisible = false;
                this.isMentalHealthVisible = false;
                this.cmsClass = this.cmsClass === "active" ? "" : "active";
                this.masterClass = "";
                this.clinicianClass = "";
                this.faqsClass = "";
                this.appointmentClass = "";
                this.blogsClass = "";
                this.newsClass = "";
                this.mentalHealthClass = "";
            }
            if (menuName === "blogs") {
                this.isCMSVisible = false;
                this.cmsClass = "";
                this.isBlogVisible = !this.isBlogVisible;
                this.isMasterVisible = false;
                this.isClinicianVisible = false;
                this.isFaqsVisible = false;
                this.isNewsVisible = false;
                this.isAppointmentVisible = false;
                this.isMentalHealthVisible = false;
                this.blogsClass = this.blogsClass === "active" ? "" : "active";
                this.masterClass = "";
                this.clinicianClass = "";
                this.faqsClass = "";
                this.appointmentClass = "";
                this.newsClass = "";
                this.mentalHealthClass = "";
            }
            if (menuName === "news") {
                this.isCMSVisible = false;
                this.cmsClass = "";
                this.isNewsVisible = !this.isNewsVisible;
                this.isMasterVisible = false;
                this.isClinicianVisible = false;
                this.isBlogVisible = false;
                this.isFaqsVisible = false;
                this.isAppointmentVisible = false;
                this.isMentalHealthVisible = false;
                this.newsClass = this.newsClass === "active" ? "" : "active";
                this.masterClass = "";
                this.clinicianClass = "";
                this.faqsClass = "";
                this.appointmentClass = "";
                this.blogsClass = "";
                this.mentalHealthClass = "";
            }
            if (menuName === "faqs") {
                this.isCMSVisible = false;
                this.cmsClass = "";
                this.isFaqsVisible = !this.isFaqsVisible;
                this.isMasterVisible = false;
                this.isClinicianVisible = false;
                this.isBlogVisible = false;
                this.isNewsVisible = false;
                this.isAppointmentVisible = false;
                this.isMentalHealthVisible = false;
                this.faqsClass = this.faqsClass === "active" ? "" : "active";
                this.masterClass = "";
                this.clinicianClass = "";
                this.appointmentClass = "";
                this.blogsClass = "";
                this.newsClass = "";
                this.mentalHealthClass = "";
            }
            if (menuName === "mental-health") {
                this.isCMSVisible = false;
                this.cmsClass = "";
                this.isMentalHealthVisible = !this.isMentalHealthVisible;
                this.isClinicianVisible = false;
                this.isBlogVisible = false;
                this.isFaqsVisible = false;
                this.isNewsVisible = false;
                this.isAppointmentVisible = false;

                this.mentalHealthClass =
                    this.mentalHealthClass === "active" ? "" : "active";
                this.masterClass = "";
                this.clinicianClass = "";
                this.faqsClass = "";
                this.appointmentClass = "";
                this.blogsClass = "";
                this.newsClass = "";
            }
        },
        changeRoute(route) {
            this.currentPath = route;
        },
        async handleLogout() {
            this.showError = false;
            this.errorMsg = null;
            this.loading = true;
            await store.dispatch("auth/logout");
            await this.$router.push({ name: "auth.login" });
        },
    },
    mounted() {
        const currentPath = this.currentPath;
        const authUser = localStorage.getItem("vuex");
        const auth = JSON.parse(authUser);
        if (
            auth.auth &&
            auth.auth.user &&
            auth.auth.user.roleData &&
            auth.auth.user.roleData.name
        ) {
            this.authUserRole = auth.auth.user.roleData.name;
            this.authUserName = auth.auth.user.name;
        }
        this.masterClass = currentPath.includes("masters") ? "active" : "";
        this.blogsClass = currentPath.includes("blog") ? "active" : "";
        this.faqsClass = currentPath.includes("faqs") ? "active" : "";
        this.newsClass = currentPath.includes("news") ? "active" : "";
        this.mentalHealthClass = currentPath.includes("mental-health")
            ? "active"
            : "";
        this.clinicianClass = currentPath.includes("clinicians")
            ? "active"
            : "";
        this.appointmentClass = currentPath.includes("appointments")
            ? "active"
            : "";
    },
};
</script>
