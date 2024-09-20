<template>
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 2xl:col-span-12">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <div class="intro-y flex justify-center items-center h-10">
                        <div>
                            <h2 class="text-lg font-medium">
                                {{ $route.meta.title }}
                            </h2>
                        </div>
                    </div>
                    <div class="intro-y flex justify-center items-center h-10">
                        <div>
                            <Datepicker
                                v-model="date"
                                format="MM-yyyy"
                                value-format="MM-yyyy"
                                auto-apply
                                placeholder="Pick a month"
                                class="p-0 border-0"
                                month-picker
                            />
                        </div>
                    </div>
                    <div class="intro-y">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-4"></div>
                            <div class="col-span-4 text-center">
                                <LoadingButton
                                    :loading="isLoading"
                                    class="mt-5"
                                    @click="handleSubmit"
                                    >Show</LoadingButton
                                >
                            </div>
                            <div class="col-span-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Activity } from "lucide-vue-next";
import apiClient from "@/services/axios";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import LoadingButton from "@/components/ui/LoadingButton.vue";

export default {
    name: "Dashboard",
    components: {
        Activity,
        Datepicker,
        LoadingButton,
    },
    data() {
        return {
            date: "",
            isLoading: false,
        };
    },
    beforeMount() {
        const newDate = new Date();
        this.date = { month: newDate.getMonth(), year: newDate.getFullYear() };
    },
    methods: {
        async handleSubmit() {
            const { month, year } = this.date || {};
            const date = month != null ? { month: (month ?? 0) + 1, year } : {};

            try {
                this.isLoading = true;
                const response = await apiClient.post(
                    `/report/balance_sheet`,
                    {
                        date: date,
                    },
                    {
                        responseType: "blob",
                    }
                );

                // Handle successful response
                if (response.status === 200) {
                    // Create a new Blob object using the response data
                    const blob = new Blob([response.data], {
                        type: "application/pdf",
                    });

                    // Create a link element
                    const link = document.createElement("a");

                    // Create a URL for the Blob and set it as the link's href
                    const url = window.URL.createObjectURL(blob);
                    link.href = url;

                    // Set the download attribute with a file name
                    link.setAttribute("download", "balance-sheet.pdf");

                    // Append the link to the body
                    document.body.appendChild(link);

                    // Trigger the download by simulating a click
                    link.click();

                    // Clean up
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(link);
                }
            } catch (error) {
                // Handle validation errors (422) or other errors
                if (error.response) {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    if (error.response.status === 404) {
                        this.$swal.showErrorToast("Data not found.");
                    }
                } else {
                    // Optional: handle other types of errors (network issues, etc.)
                    console.error("Unexpected error:", error);
                }
            } finally {
                this.isLoading = false;
            }
        },
    },
    mounted() {},
};
</script>

<style scoped></style>
