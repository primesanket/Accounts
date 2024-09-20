import axios from 'axios';
import { getTokenCookie, removeTokenCookie } from '../utils/cookies';
import { showErrorToast } from '@/services/sweetalert2Service';

const api = axios.create({
    baseURL: import.meta.env.VITE_APP_BACKEND_URL_API,
    withCredentials: true,
    headers: {
        'Content-Type': 'application/json',
    },
});

api.interceptors.request.use(
    (config) => {
        const token = getTokenCookie();
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

api.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        const token = getTokenCookie();
        if (error.response.status === 401 && token) {
            removeTokenCookie();
            window.location.href = "/";
        }
        if (axios.isAxiosError(error)) {
            showErrorToast(error.response?.data?.message);
        }

        return Promise.reject(error.response);
    }
);


export default api;
