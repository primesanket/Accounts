<template>
    <PageTitle :isBackButtonAllow="false" class="mb-5" />
    <div class="intro-y col-span-12 lg:col-span-6">
        <!-- BEGIN: Input -->
        <form @submit.prevent="handleSubmit">
            <div class="intro-y">
                <div class="py-5" v-if="isDataLoading">
                    <img
                        class="loader mx-auto"
                        src="../../assets/images/loader.svg"
                    />
                </div>
                <div v-else>
                    <div class="box p-5">
                        <div class="border p-5 mt-3">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-3">
                                    <label for="from_date" class="form-label">
                                        From Date
                                    </label>

                                    <Datepicker
                                        type="date"
                                        v-model="form.from_date"
                                        format="dd-MM-yyyy"
                                        value-format="dd-MM-yyyy"
                                        auto-apply
                                        placeholder="Pick a date"
                                        class="p-0 border-0"
                                    />

                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.from_date"
                                    >
                                        {{ errors.from_date[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label for="to_date" class="form-label">
                                        To Date
                                    </label>

                                    <Datepicker
                                        type="date"
                                        v-model="form.to_date"
                                        format="dd-MM-yyyy"
                                        value-format="dd-MM-yyyy"
                                        auto-apply
                                        placeholder="Pick a date"
                                        class="p-0 border-0"
                                    />

                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.to_date"
                                    >
                                        {{ errors.to_date[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label
                                        for="expense_type_id"
                                        class="form-label"
                                    >
                                        Expense Type
                                    </label>
                                    <multiselect
                                        track-by="text"
                                        label="text"
                                        v-model="form.expense_type_id"
                                        :options="expense_types"
                                        placeholder="Select Expense Type"
                                    ></multiselect>
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.expense_type_id"
                                    >
                                        {{ errors.expense_type_id[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label for="receiver_id" class="form-label">
                                        Done By
                                    </label>
                                    <multiselect
                                        track-by="name"
                                        label="name"
                                        v-model="form.receiver_id"
                                        :options="receivers"
                                        placeholder="Select Receiver"
                                    ></multiselect>
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.expense_account_id"
                                    >
                                        {{ errors.expense_account_id[0] }}
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
                                    >Show</LoadingButton
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
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Multiselect from "vue-multiselect";

export default {
    components: {
        LoadingButton,
        Datepicker,
        Multiselect,
    },
    data() {
        return {
            form: {
                from_date: new Date(),
                to_date: new Date(),
                expense_type_id: "",
                receiver_id: "",
            },
            expense_types: [],
            receivers: [],
            errors: [],
            isDataLoading: false,
            isLoading: false,
        };
    },
    beforeMount() {
        this.currentMonthDates();
        this.fetchBootData();
    },
    methods: {
        currentMonthDates() {
            const currentDate = new Date();
            this.form.from_date = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth(),
                1
            );
            this.form.to_date = new Date(
                currentDate.getFullYear(),
                currentDate.getMonth() + 1,
                0
            );
        },
        async fetchBootData() {
            try {
                this.isDataLoading = true;
                const [expense_types, expense_accounts] = await Promise.all([
                    apiClient.get(`/drop-down/expense_types`),
                    apiClient.get(`/drop-down/expense_accounts`),
                ]);
                this.expense_types = expense_types.data;
                this.receivers = expense_accounts.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.isDataLoading = false;
            }
        },
        async handleSubmit() {
            this.errors = [];
            const isValid = this.checkValidData();
            if (isValid) {
                try {
                    this.isLoading = true;
                    const formattedFromDate = this.form.from_date
                        ? this.formatDate(new Date(this.form.from_date))
                        : null;
                    const formattedToDate = this.form.to_date
                        ? this.formatDate(new Date(this.form.to_date))
                        : null;

                    const { expense_type_id, receiver_id } = this.form;

                    const formData = {
                        ...this.form,
                        from_date: formattedFromDate,
                        to_date: formattedToDate,
                        expense_type_id: expense_type_id?.id || null,
                        expense_account_id: receiver_id?.id || null,
                    };

                    const response = await apiClient.post(
                        `/report/expense`,
                        formData,
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
                        link.setAttribute("download", "expense-report.pdf");

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
            }
        },
        checkValidData() {
            const from_date = this.form.from_date;
            const to_date = this.form.to_date;

            // Check if the amount is provided
            if (from_date == null || to_date == null) {
                this.$swal.showErrorToast("The both dates are required.");
                return false;
            }

            // If all checks pass, return true
            return true;
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

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
.head-bg {
    background-color: rgb(var(--color-slate-100) / var(--tw-bg-opacity));
}
</style>
