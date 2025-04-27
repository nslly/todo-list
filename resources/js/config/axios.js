import axios from 'axios'

// allow sending cookies / authorization headers
axios.defaults.withCredentials = true;

axios.defaults.baseURL = 'http://127.0.0.1:8000/api/v1';

// intercept requests before sending
axios.interceptors.request.use(function (config) {
    return config;
}, function (error) {
    return Promise.reject(error);
});

export default axios;
