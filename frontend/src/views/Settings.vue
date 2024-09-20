<template>
    <PageTitle :isBackButtonAllow="false" class="mb-5" />
    <div class="intro-y col-span-12 lg:col-span-6">
        <!-- BEGIN: Input -->
        <form @submit.prevent="handleSubmit">
            <div class="intro-y">
                <div class="py-5" v-if="isDataLoading">
                    <img
                        class="loader mx-auto"
                        src="@/assets/images/loader.svg"
                    />
                </div>
                <div v-else>
                    <div class="box p-5">
                        <h2 class="text-md text-primary font-medium truncate">
                            Opening Balance
                        </h2>
                        <div class="border p-5 mt-3">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-4">
                                    <label for="mehul_opening_balance" class="form-label">
                                        Mehul
                                    </label>
                                    <input
                                        id="mehul_opening_balance"
                                        type="number"
                                        v-model="form.mehul_opening_balance"
                                        class="form-control"
                                        placeholder=""
                                        step="any"
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.mehul_opening_balance"
                                    >
                                        {{ errors.mehul_opening_balance[0] }}
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label for="bhavin_opening_balance" class="form-label">
                                        Bhavin
                                    </label>
                                    <input
                                        id="bhavin_opening_balance"
                                        type="number"
                                        v-model="form.bhavin_opening_balance"
                                        class="form-control"
                                        placeholder=""
                                        step="any"
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.bhavin_opening_balance"
                                    >
                                        {{ errors.bhavin_opening_balance[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="intro-y">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-4"></div>
                            <div class="col-span-4 text-center">
                                <LoadingButton
                                    v-if="!isDataLoading"
                                    :loading="isLoading"
                                    class="mt-5"
                                    :disabled="isLoading"
                                    >Submit</LoadingButton
                                >
                            </div>
                            <div class="col-span-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- END: Input -->
    </div>
</template>

<script>
import apiClient from "@/services/axios";
import LoadingButton from "@/components/ui/LoadingButton.vue";

export default {
    components: {
        LoadingButton,
    },
    data() {
        return {
            form: {
                mehul_opening_balance: 0,
                bhavin_opening_balance: 0,
            },
            errors: [],
            isDataLoading: false,
            isLoading: false,
        };
    },
    beforeMount() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                this.isDataLoading = true;
                const response = await apiClient.get(`/setting`);
                if (response && response.data) {
                    this.form = {
                        mehul_opening_balance: response.data?.mehul_opening_balance || 0,
                        bhavin_opening_balance: response.data?.bhavin_opening_balance || 0,
                    };
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.isDataLoading = false;
            }
        },
        async handleSubmit() {
            this.errors = [];
            this.isLoading = true;
            try {
                // Extract and format IDs from the form
                const { mehul_opening_balance, bhavin_opening_balance } =
                    this.form;

                // Prepare form data to send
                const formData = {
                    ...this.form,
                    mehul_opening_balance: parseFloat(
                        mehul_opening_balance || 0
                    ),
                    bhavin_opening_balance: parseFloat(
                        bhavin_opening_balance || 0
                    ),
                };

                // Send the POST request
                const response = await apiClient.post(
                    "/update-setting",
                    formData
                );

                // Handle successful response
                if (response.status === 201) {
                    this.$swal.showSuccessToast(response.data.message);
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 500);
                }
            } catch (error) {
                this.isLoading = false;
                // Handle validation errors (422) or other errors
                if (error.response) {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                } else {
                    // Optional: handle other types of errors (network issues, etc.)
                    console.error("Unexpected error:", error);
                }
            }
        },
        formatDate(date, format = null) {
            if (!(date instanceof Date)) {
                throw new Error("Input must be a Date object");
            }

            const day = String(date.getDate()).padStart(2, "0");
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const year = date.getFullYear();

            if (format == "ddmmyyyy") {
                return `${day}-${month}-${year}`;
            } else {
                return `${year}-${month}-${day}`;
            }
        },
    },
    computed: {},
    watch: {},
    filters: {},
    mounted() {},
};
</script>

<style scoped></style>
