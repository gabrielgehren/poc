import axios from 'axios';
import rateLimit from 'axios-rate-limit';

const axiosInstance = rateLimit(axios.create({
    timeout: 5000
}), { maxRequests: 1, perMilliseconds: 100, maxRPS: 1 });

axiosInstance.interceptors.response.use(
    response => response,
    error => {
        return Promise.reject(error);
    }
);

export default axiosInstance;