<template>
    <PageTitle :isBackButtonAllow="true" class="mb-5" />
    <div class="intro-y col-span-12 lg:col-span-6">
        <!-- BEGIN: Input -->
        <form @submit.prevent="handleSubmit">
            <div class="intro-y box">
                <div class="py-5" v-if="isDataLoading">
                    <img
                        class="loader mx-auto"
                        src="../../assets/images/loader.svg"
                    />
                </div>
                <div id="input" class="p-5" v-else>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-3">
                            <label for="type" class="form-label">
                                Type <span class="text-danger">*</span>
                            </label>
                            <multiselect
                                track-by="type"
                                label="type"
                                v-model="form.expense_type_id"
                                :options="expense_types"
                                placeholder="Select or Add Expense Type"
                                :taggable="true"
                                @tag="addExpenseType"
                            ></multiselect>
                            <div
                                class="text-danger mt-2"
                                v-if="errors.expense_type_id"
                            >
                                {{ errors.expense_type_id[0] }}
                            </div>
                        </div>
                        <div class="col-span-3">
                            <label for="expense_date" class="form-label">
                                Date <span class="text-danger">*</span>
                            </label>

                            <Datepicker
                                type="date"
                                v-model="form.expense_date"
                                format="dd-MM-yyyy"
                                value-format="dd-MM-yyyy"
                                auto-apply
                                placeholder="Pick a date"
                                class="p-0 border-0"
                            />

                            <div
                                class="text-danger mt-2"
                                v-if="errors.expense_date"
                            >
                                {{ errors.expense_date[0] }}
                            </div>
                        </div>
                        <div class="col-span-3">
                            <label for="amount" class="form-label">
                                Amount <span class="text-danger">*</span>
                            </label>
                            <div class="relative mx-auto">
                                <div
                                    class="absolute rounded-l w-10 h-full flex items-center justify-center bg-slate-100 border text-slate-500 dark:bg-darkmode-700 dark:border-darkmode-800 dark:text-slate-400"
                                >
                                    <IndianRupee size="18" />
                                </div>
                                <input
                                    id="amount"
                                    type="number"
                                    v-model="form.amount"
                                    placeholder=""
                                    class="datepicker form-control pl-12"
                                    step="any"
                                />
                            </div>
                            <div class="text-danger mt-2" v-if="errors.amount">
                                {{ errors.amount[0] }}
                            </div>
                        </div>
                        <div class="col-span-3">
                            <label for="account_by" class="form-label">
                                Account By <span class="text-danger">*</span>
                            </label>
                            <multiselect
                                track-by="name"
                                label="name"
                                v-model="form.expense_account_id"
                                :options="expense_accounts"
                                placeholder="Select Account"
                            ></multiselect>
                            <div
                                class="text-danger mt-2"
                                v-if="errors.expense_account_id"
                            >
                                {{ errors.expense_account_id[0] }}
                            </div>
                        </div>
                        <div class="col-span-12">
                            <label for="description" class="form-label">
                                Description
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                class="form-control"
                                placeholder=""
                                rows="5"
                            />
                            <div class="text-danger mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <LoadingButton
                v-if="!isDataLoading"
                :loading="isLoading"
                class="mt-5"
                >Submit</LoadingButton
            >
        </form>
        <!-- END: Input -->
    </div>
</template>

<script>
import apiClient from "@/services/axios";
import LoadingButton from "@/components/ui/LoadingButton.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { IndianRupee } from "lucide-vue-next";
import Multiselect from "vue-multiselect";

export default {
    components: {
        LoadingButton,
        Datepicker,
        IndianRupee,
        Multiselect,
    },
    data() {
        return {
            form: {
                expense_type_id: "",
                expense_date: "",
                amount: "",
                description: "",
                expense_account_id: "",
            },
            expense_types: [],
            expense_accounts: [],
            errors: [],
            isDataLoading: false,
            isLoading: false,
        };
    },
    beforeMount() {
        this.fetchBootData();
    },
    methods: {
        async fetchBootData() {
            try {
                this.isDataLoading = true;
                const expense_types = await apiClient.get(`/expense-types`);
                const expense_accounts = await apiClient.get(
                    `/drop-down/expense_accounts`
                );
                this.expense_types = expense_types.data;
                this.expense_accounts = expense_accounts.data;
                setTimeout(() => {
                    this.isDataLoading = false;
                }, 300);
            } catch (error) {
                this.isDataLoading = false;
                console.error("Fetch data error:", error);
            }
        },
        async addExpenseType(newType) {
            try {
                this.isLoading = true;
                const formData = {
                    type: newType,
                };
                const response = await apiClient.post(
                    "/expense-types",
                    formData
                );
                this.isLoading = false;
                if (response.status === 201) {
                    this.expense_types.push(response.data.data);
                    this.form.expense_type_id = response.data.data;
                }
            } catch (error) {
                if (error.response) {
                    this.isLoading = false;
                }
            }
        },
        async handleSubmit() {
            try {
                this.isLoading = true;
                let formattedDate = null;
                if (this.form.expense_date) {
                    formattedDate = this.formatDate(
                        new Date(this.form.expense_date)
                    );
                }
                const expense_type_id = this.form.expense_type_id
                    ? this.form.expense_type_id.id
                    : null;
                const expense_account_id = this.form.expense_account_id
                    ? this.form.expense_account_id.id
                    : null;
                const formData = {
                    ...this.form,
                    expense_date: formattedDate,
                    expense_type_id: expense_type_id,
                    expense_account_id: expense_account_id,
                };
                const response = await apiClient.post("/expense", formData);
                if (response.status === 201) {
                    this.$swal.showSuccessToast(response.data.message);
                    setTimeout(() => {
                        this.isLoading = false;
                        this.$router.push("/expense");
                    }, 500);
                }
            } catch (error) {
                if (error.response) {
                    this.isLoading = false;
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                }
            }
        },
        formatDate(date) {
            if (!(date instanceof Date)) {
                throw new Error("Input must be a Date object");
            }

            const day = String(date.getDate()).padStart(2, "0");
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const year = date.getFullYear();

            return `${year}-${month}-${day}`;
        },
    },
    mounted() {},
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
