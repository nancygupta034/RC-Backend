/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { createApp } from 'vue';
import common from "@/mixins/common";
import router from './routes/index'
import App from './views/App.vue';
import store from './store';
import VeeValidatePlugin from "./plugin/validation";
import '@/assets/scss/app.scss';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueTransitions from '@morev/vue-transitions';
import '@morev/vue-transitions/styles';
import CKEditor from '@ckeditor/ckeditor5-vue';
import dayjs from 'dayjs';

const app = createApp(App);
app.config.globalProperties.$dayjs = dayjs
app.use(VueSweetalert2);
app.mixin(common);
app.use(VeeValidatePlugin);
app.use(router);
app.use( CKEditor );
app.use( VueSweetalert2  );
app.use(VueTransitions);
app.use(store);
app.mount('#app');
