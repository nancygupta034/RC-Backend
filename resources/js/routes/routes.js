import AuthLayout from '../layouts/Auth.vue';
import auth from '../middleware/auth';
import guest from '../middleware/guest';
import admin from '../middleware/admin';
import cms from '../middleware/cms';
import intake from '../middleware/intake';

export default [
    {
        name: 'auth.login',
        path: '',
        beforeEnter: [guest],
        component: () => import('../views/auth/login.vue')
    },
    {
        path: '',
        component: AuthLayout,
        beforeEnter: [auth],
        children: [
            {
                name: 'admin.dashboard',
                path: 'dashboard',
                component: () => import('../views/admin/dashboard/index.vue'),
                beforeEnter: [intake],
            },
            {
                name: 'admin.specialities',
                path: 'masters/specialities',
                component: () => import('../views/admin/specialities/index.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.categories',
                path: 'masters/categories',
                component: () => import('../views/admin/categories/index.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.testing-types',
                path: 'masters/testing-types',
                component: () => import('../views/admin/testing-types/index.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.services-provided',
                path: 'masters/services-provided',
                component: () => import('../views/admin/services-provided/index.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.age-ranges',
                path: 'masters/age-ranges',
                component: () => import('../views/admin/age-ranges/index.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.states-covered',
                path: 'masters/states-covered',
                component: () => import('../views/admin/states-covered/index.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.users',
                path: 'masters/users',
                component: () => import('../views/admin/users/index.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.clinicians.list',
                path: 'clinicians/list',
                component: () => import('../views/admin/clinicians/index.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.clinicians.add',
                path: 'clinicians/add',
                component: () => import('../views/admin/clinicians/add.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.clinicians.edit',
                path: 'clinicians/edit/:clinicianId',
                component: () => import('../views/admin/clinicians/edit.vue'),
                beforeEnter: [admin],
            },
            {
                name: 'admin.testing-requests',
                path: 'testing-requests',
                component: () => import('../views/testing-requests/index.vue'),
                beforeEnter: [intake],
            },
            {
                name: 'admin.testing-requests.view',
                path: 'testing-requests/:requestId',
                component: () => import('../views/testing-requests/view.vue'),
                beforeEnter: [intake],
            },
            {
                name: 'admin.our-story',
                path: '/cms/our-story/data',
                component: () => import('../views/cms/our-story/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.referral-data',
                path: 'cms/referral/data',
                component: () => import('../views/cms/referral/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.opportunity-data',
                path: 'cms/opportunity/data',
                component: () => import('../views/cms/opportunity/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.appointment-data',
                path: 'cms/appointment/data',
                component: () => import('../views/cms/appointment-terms/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.psych-testing-data',
                path: 'cms/psych-testing/data',
                component: () => import('../views/cms/psych-testing-terms/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.services',
                path: 'services/list',
                component: () => import('../views/services/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.services.add',
                path: 'services/add',
                component: () => import('../views/services/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.services.edit',
                path: 'services/edit/:serviceId',
                component: () => import('../views/services/edit.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.blogs',
                path: 'blogs/list',
                component: () => import('../views/cms/blogs/blogs/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.blogs.add',
                path: 'blogs/add',
                component: () => import('../views/cms/blogs/blogs/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.blogs.edit',
                path: 'blogs/edit/:blogId',
                component: () => import('../views/cms/blogs/blogs/edit.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.categories',
                path: 'blog-categories',
                component: () => import('../views/cms/blogs/categories/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.mental-health',
                path: 'mental-health/list',
                component: () => import('../views/cms/mental-health/mental-health/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.mental-health.add',
                path: 'mental-health/add',
                component: () => import('../views/cms/mental-health/mental-health/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.mental-health.edit',
                path: 'mental-health/edit/:blogId',
                component: () => import('../views/cms/mental-health/mental-health/edit.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.mental-health.categories',
                path: 'mental-health-categories',
                component: () => import('../views/cms/mental-health/categories/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.news',
                path: 'news/list',
                component: () => import('../views/cms/news/news/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.news.add',
                path: 'news/add',
                component: () => import('../views/cms/news/news/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.news.edit',
                path: 'news/edit/:newsId',
                component: () => import('../views/cms/news/news/edit.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.news-categories',
                path: 'news-categories',
                component: () => import('../views/cms/news/categories/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.faqs',
                path: 'faqs/list',
                component: () => import('../views/cms/faq/faq/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.faqs.add',
                path: 'faqs/add',
                component: () => import('../views/cms/faq/faq/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.faqs.edit',
                path: 'faqs/edit/:faqsId',
                component: () => import('../views/cms/faq/faq/edit.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.faqs-categories',
                path: 'faqs-categories',
                component: () => import('../views/cms/faq/categories/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.teams',
                path: 'team-members',
                component: () => import('../views/teams/index.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.teams.add',
                path: 'team-members/add',
                component: () => import('../views/teams/add.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.teams.edit',
                path: 'team-members/edit/:teamId',
                component: () => import('../views/teams/edit.vue'),
                beforeEnter: [cms],
            },
            {
                name: 'admin.appointments.confirmed',
                path: 'confirmed-appointments',
                component: () => import('../views/confirmed-appointments/index.vue'),
                beforeEnter: [intake],
            },
            {
                name: 'admin.appointments.pending',
                path: 'pending-appointments',
                component: () => import('../views/pending-appointments/index.vue'),
                beforeEnter: [intake],
            },
            {
                name: 'admin.appointments.referral',
                path: 'referral-appointments',
                component: () => import('../views/referral-appointments/index.vue'),
                beforeEnter: [intake],
            },
            {
                name: 'admin.confirmed-appointments.view',
                path: 'confirmed-appointments/:appointmentId',
                component: () => import('../views/confirmed-appointments/view.vue'),
                beforeEnter: [intake],
            },
            {
                name: 'admin.pending-appointments.view',
                path: 'pending-appointments/:appointmentId',
                component: () => import('../views/pending-appointments/view.vue'),
                beforeEnter: [intake],
            },
            {
                name: 'admin.referral-appointments.view',
                path: 'referral-appointments/:appointmentId',
                component: () => import('../views/referral-appointments/view.vue'),
                beforeEnter: [intake],
            },
        ]
    },
    {
        path: '/',
        component: AuthLayout,
        beforeEnter: [auth],
        children: [

            {
                name: 'change-password',
                path: 'change-password',
                component: () => import('../views/change-password.vue'),
            },
        ]
    },
    {
        name: 'errors.404',
        path: "/:pathMatch(.*)*",
        component: () => import('../views/errors/404.vue')
    },
    {
        name: 'errors.403',
        path: "/403",
        component: () => import('../views/errors/403.vue')
    }
];