import axios from "axios";
import { useAuthStore } from "@/stores/auth";
import swal from "@/services/sweetalert2Service";

const apiClient = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL + "/api", // Laravel API URL
    headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
    },
    withCredentials: false,
});

apiClient.interceptors.request.use(
    (config) => {
        const authStore = useAuthStore();
        if (authStore.token) {
            config.headers.Authorization = `Bearer ${authStore.token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        const authStore = useAuthStore();
        if (error.response) {
            if (error.response.status === 401 && window.location.pathname !== '/') {
                authStore.clear();
                window.location.href = "/";
            } else if ([419, 403].includes(error.response.status)) {
                authStore.logout();
            } else if ([404].includes(error.response.status)) {
            } else {
                if (error.response.data.error) {
                    swal.showErrorToast(error.response.data.error);
                } else {
                    swal.showErrorToast(
                        error.response.data.message || "Something went wrong!"
                    );
                }
            }
        }
        return Promise.reject(error);
    }
);

export default apiClient;
