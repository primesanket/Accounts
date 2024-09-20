import { defineStore } from "pinia";
import apiClient from "@/services/axios";
import swal from "@/services/sweetalert2Service";
import Cookies from "js-cookie";

export const useAuthStore = defineStore("auth", {
    state: () => ({
        token: Cookies.get("token") || "",
        user: JSON.parse(Cookies.get("user") || "null"),
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
        userInfo: (state) => state.user,
    },
    actions: {
        async login(credentials) {
            Cookies.remove("token");
            Cookies.remove("user");
            try {
                const response = await apiClient.post("/login", credentials);
                this.token = response.data?.token;
                Cookies.set("token", this.token, { expires: 1 / 12 }); // 2 hours
                apiClient.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;
                return response;
            } catch (error) {
                const { status, data } = error.response;
                const errorMessage =
                    data.error || data.message || "Something went wrong!";
                swal.showErrorToast(errorMessage);

                console.error("Login error:", error);
                // Handle error appropriately, e.g., show a notification to the user
                return error.response;
            }
        },
        async register(userData) {
            try {
                const response = await apiClient.post("/register", userData);
                this.token = response.data.token;
                this.user = response.data.user;
                Cookies.set("token", this.token, { expires: 1 / 12 }); // 2 hours
                Cookies.set("user", JSON.stringify(this.user), {
                    expires: 1 / 12,
                }); // 2 hours
                apiClient.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${this.token}`;
            } catch (error) {
                console.error("Registration error:", error);
                // Handle error appropriately, e.g., show a notification to the user
            }
        },
        logout() {
            try {
                apiClient.post("/logout");
                this.clear();
            } catch (error) {
                console.error("Logout error:", error);
                // Handle error appropriately, e.g., show a notification to the user
            }
        },
        clear() {
            this.token = "";
            this.user = null;
            Cookies.remove("token");
            Cookies.remove("user");
            delete apiClient.defaults.headers.common["Authorization"];
            window.location.href = "/";
        },
        async fetchUserData() {
            try {
                const response = await apiClient.get("/user");
                this.user = response.data;
                Cookies.set("user", JSON.stringify(this.user), {
                    expires: 1 / 12,
                }); // 2 hours
            } catch (error) {
                console.error("Fetch user data error:", error);
                // Handle error appropriately, e.g., show a notification to the user
            }
        },
    },
});
