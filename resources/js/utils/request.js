import '@/bootstrap';
// import { Message } from 'element-ui';
import { isLogged, getToken } from '@/utils/auth';

// Create axios instance
const service = window.axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 10000, // Request timeout
});

// Request intercepter
service.interceptors.request.use(

  config => {
    const token = isLogged();
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + getToken(); // Set JWT token
    }
    return config;
  },
  error => {
    Promise.reject(error);
  }
);

// response pre-processing
service.interceptors.response.use(
  response => {

    return response.data;
  },
  error => {
    let message = error.message;
    if (error.response.data && error.response.data.errors) {
      message = error.response.data.errors;
    } else if (error.response.data && error.response.data.error) {
      message = error.response.data.error;
    }
    return Promise.reject(error);
  }
);

export default service;
