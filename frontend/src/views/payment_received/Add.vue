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
                            Party Bill Details
                        </h2>
                        <div class="border p-5 mt-3">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-4">
                                    <label for="firm_id" class="form-label">
                                        Firm <span class="text-danger">*</span>
                                    </label>
                                    <multiselect
                                        track-by="firm_name"
                                        label="firm_name"
                                        v-model="form.firm_id"
                                        :options="firms"
                                        placeholder="Select Firm"
                                    ></multiselect>
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.firm_id"
                                    >
                                        {{ errors.firm_id[0] }}
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label for="party_id" class="form-label">
                                        Party <span class="text-danger">*</span>
                                    </label>
                                    <multiselect
                                        track-by="party_name"
                                        label="party_name"
                                        v-model="form.party_id"
                                        :options="parties"
                                        placeholder="Select Party"
                                    ></multiselect>
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.party_id"
                                    >
                                        {{ errors.party_id[0] }}
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label for="bill_id" class="form-label">
                                        Bill No
                                        <span class="text-danger">*</span>
                                    </label>
                                    <multiselect
                                        track-by="bill_no"
                                        label="bill_no"
                                        v-model="form.bill_id"
                                        :options="bills"
                                        placeholder="Select Bill"
                                        :disabled="!bills.length"
                                    ></multiselect>
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.sale_id"
                                    >
                                        {{ errors.sale_id[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label for="sale_date" class="form-label">
                                        Sale Date
                                    </label>

                                    <Datepicker
                                        type="date"
                                        v-model="form.sale_date"
                                        format="dd-MM-yyyy"
                                        value-format="dd-MM-yyyy"
                                        auto-apply
                                        placeholder="Pick a date"
                                        class="p-0 border-0"
                                        :disabled="true"
                                    />

                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.sale_date"
                                    >
                                        {{ errors.sale_date[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label for="date" class="form-label">
                                        Date
                                        <span class="text-danger">*</span>
                                    </label>

                                    <Datepicker
                                        type="date"
                                        v-model="form.date"
                                        format="dd-MM-yyyy"
                                        value-format="dd-MM-yyyy"
                                        auto-apply
                                        placeholder="Pick a date"
                                        class="p-0 border-0"
                                    />

                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.date"
                                    >
                                        {{ errors.date[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label
                                        for="payment_type_id"
                                        class="form-label"
                                    >
                                        Payment Type
                                        <span class="text-danger">*</span>
                                    </label>
                                    <multiselect
                                        track-by="text"
                                        label="text"
                                        v-model="form.payment_type_id"
                                        :options="payment_types"
                                        placeholder="Select Payment Type"
                                    ></multiselect>
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.payment_type_id"
                                    >
                                        {{ errors.payment_type_id[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label for="receiver_id" class="form-label">
                                        Received By
                                        <span class="text-danger">*</span>
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
                                <div class="col-span-12">
                                    <div class="divider my-2 mx-1"></div>
                                </div>
                                <div class="col-span-4">
                                    <label for="discount" class="form-label">
                                        Discount
                                    </label>
                                    <input
                                        id="discount"
                                        type="number"
                                        v-model="form.discount"
                                        class="form-control"
                                        placeholder=""
                                        step="any"
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.discount"
                                    >
                                        {{ errors.discount[0] }}
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label for="discount" class="form-label">
                                        Amount
                                    </label>
                                    <input
                                        id="amount"
                                        type="number"
                                        v-model="form.amount"
                                        class="form-control"
                                        placeholder=""
                                        step="any"
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.amount"
                                    >
                                        {{ errors.amount[0] }}
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label for="remark" class="form-label">
                                        Remark
                                    </label>
                                    <input
                                        id="remark"
                                        type="text"
                                        v-model="form.remark"
                                        class="form-control"
                                        placeholder=""
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="errors.remark"
                                    >
                                        {{ errors.remark[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="overflow-x-auto lg:overflow-visible mt-5"
                            v-if="payments && payments.length"
                        >
                            <table
                                class="table table-sm table-bordered table-hover"
                            >
                                <thead class="head-bg">
                                    <tr>
                                        <th
                                            v-for="column in columns"
                                            :key="column.key"
                                            :class="column.class"
                                            class="uppercase"
                                        >
                                            {{ column.label }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-if="payments && payments.length > 0"
                                        v-for="(item, index) in payments"
                                        :key="index"
                                    >
                                        <td
                                            v-for="column in columns"
                                            :key="column.key"
                                            :class="column.class"
                                        >
                                            <span v-if="column.key === 'sr'">
                                                {{ index + 1 }}
                                            </span>
                                            <span
                                                v-else-if="
                                                    column.key === 'date'
                                                "
                                            >
                                                {{
                                                    formatDate(
                                                        new Date(
                                                            item[column.key]
                                                        ),
                                                        "ddmmyyyy"
                                                    )
                                                }}
                                            </span>
                                            <span
                                                v-else-if="
                                                    column.key === 'action'
                                                "
                                            >
                                                <span class="flex">
                                                    <button
                                                        type="button"
                                                        @click="
                                                            deleteAction(item)
                                                        "
                                                        class="btn btn-sm outline-none text-danger"
                                                    >
                                                        <Trash2
                                                            size="16"
                                                            class="mr-1"
                                                        />
                                                        Remove
                                                    </button>
                                                </span>
                                            </span>
                                            <span v-else>
                                                {{ item[column.key] }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-else>
                                        <td colspan="100" class="text-center">
                                            No matching records found
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="intro-y">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-4">
                                <div class="intro-x mt-5 text-left">
                                    <span class="text-lg font-medium"
                                        >Total Amount :
                                    </span>
                                    <span class="text-md font-medium"
                                        >₹ {{ totalAmount }}</span
                                    >
                                </div>
                            </div>
                            <div class="col-span-4 text-center">
                                <LoadingButton
                                    v-if="!isDataLoading"
                                    :loading="isLoading"
                                    class="mt-5"
                                    :disabled="!parseFloat(total_amount)"
                                    >Submit</LoadingButton
                                >
                            </div>
                            <div class="col-span-4">
                                <div class="intro-x mt-5 text-right">
                                    <span class="text-lg font-medium"
                                        >Pending Amount :
                                    </span>
                                    <span class="text-md font-medium"
                                        >₹ {{ pendingAmount }}</span
                                    >
                                </div>
                            </div>
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
import { IndianRupee, Trash2 } from "lucide-vue-next";
import Multiselect from "vue-multiselect";

export default {
    components: {
        LoadingButton,
        Datepicker,
        IndianRupee,
        Trash2,
        Multiselect,
    },
    data() {
        return {
            form: {
                firm_id: "",
                party_id: "",
                bill_id: "",
                sale_date: new Date(),
                date: new Date(),
                payment_type_id: "",
                receiver_id: "",
                discount: 0,
                amount: 0,
                remark: "",
            },
            total_amount: 0,
            pending_amount: 0,
            columns: [
                { label: "Sr.", key: "sr", class: "whitespace-nowrap w-10" },
                { label: "Date", key: "date", class: "whitespace-nowrap w-20" },
                {
                    label: "Discount",
                    key: "discount",
                    class: "whitespace-nowrap w-20",
                },
                {
                    label: "Amount",
                    key: "amount",
                    class: "whitespace-nowrap w-20",
                },
                {
                    label: "Payment Via",
                    key: "payment_type",
                    class: "whitespace-nowrap w-20",
                },
                {
                    label: "Received By",
                    key: "expense_account",
                    class: "whitespace-nowrap w-20",
                },
                {
                    label: "Remark",
                    key: "remark",
                    class: "whitespace-nowrap",
                },
                { label: "Action", key: "action", class: "text-center w-10" },
            ],
            payments: [],
            firms: [],
            parties: [],
            bills: [],
            payment_types: [],
            receivers: [],
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
                const [firms, parties, payment_types, expense_accounts] =
                    await Promise.all([
                        apiClient.get(`/drop-down/firms`),
                        apiClient.get(`/drop-down/parties`),
                        apiClient.get(`/drop-down/payment_types`),
                        apiClient.get(`/drop-down/expense_accounts`),
                    ]);
                this.firms = firms.data;
                this.parties = parties.data;
                this.payment_types = payment_types.data;
                this.receivers = expense_accounts.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.isDataLoading = false;
            }
        },
        async handleSubmit() {
            this.errors = [];
            const isValid = this.checkValidAmount();
            if (isValid) {
                this.isLoading = true;
                try {
                    // Format sale_date and due_date if available
                    const formattedSaleDate = this.form.sale_date
                        ? this.formatDate(new Date(this.form.sale_date))
                        : null;
                    const formattedDate = this.form.date
                        ? this.formatDate(new Date(this.form.date))
                        : null;

                    // Extract and format IDs from the form
                    const {
                        firm_id,
                        party_id,
                        bill_id,
                        payment_type_id,
                        receiver_id,
                        discount,
                        amount,
                    } = this.form;

                    // Prepare form data to send
                    const formData = {
                        ...this.form,
                        sale_date: formattedSaleDate,
                        date: formattedDate,
                        sale_id: bill_id?.id || null,
                        firm_id: firm_id?.id || null,
                        party_id: party_id?.id || null,
                        payment_type_id: payment_type_id?.id || null,
                        expense_account_id: receiver_id?.id || null,
                        discount: parseFloat(discount || 0),
                        amount: parseFloat(amount || 0),
                    };

                    // Send the POST request
                    const response = await apiClient.post(
                        "/payment-receive",
                        formData
                    );

                    // Handle successful response
                    if (response.status === 201) {
                        this.$swal.showSuccessToast(response.data.message);
                        this.resetFields();
                        this.form.party_id = "";
                        this.form.firm_id = "";
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
            }
        },
        async getBills() {
            this.resetFields();
            try {
                const [bills] = await Promise.all([
                    apiClient.post(`/drop-down/bills`, {
                        party_id: this.form.party_id,
                    }),
                ]);
                this.bills = bills.data;
            } catch (error) {
                console.error(error);
            } finally {
            }
        },
        async getPayments() {
            try {
                const response = await apiClient.get(`/payment-receive`, {
                    params: { sale_id: this.form.bill_id.id },
                });
                this.payments = response.data;
            } catch (error) {
                console.error(error);
            } finally {
            }
        },
        async deleteAction(row) {
            const confirmed = await this.$swal.showConfirm(
                "Are you sure?",
                `Are you sure you want to delete?`
            );
            if (confirmed) {
                try {
                    const response = await apiClient.delete(
                        `/payment-receive/${row.id}`
                    );
                    this.$swal.showSuccessToast("Deleted successfully!");
                    await this.getPayments();
                    const idToUpdate = this.form.bill_id.id;
                    const object = this.bills.find(
                        (item) => item.id === idToUpdate
                    );

                    const currentAmount =
                        parseFloat(object.received_amount) || 0;
                    const discount =
                        parseFloat(row.discount.replace(/[^\d.-]/g, "")) || 0;
                    const amount =
                        parseFloat(row.amount.replace(/[^\d.-]/g, "")) || 0;

                    object.received_amount =
                        currentAmount - (discount + amount);
                } catch (error) {
                    console.error("Delete error:", error);
                }
            } else {
                this.$swal.showErrorToast("Deletion has been canceled.");
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
        checkValidAmount() {
            const amount = parseFloat(this.form.amount);
            const pendingAmount = parseFloat(this.pending_amount);

            // Check if the amount is provided
            if (amount == null || amount === "") {
                this.$swal.showErrorToast("The amount is required.");
                return false;
            }

            // Check if the amount is a valid number
            if (typeof amount !== "number" || isNaN(amount)) {
                this.$swal.showErrorToast("The amount is invalid.");
                return false;
            }

            // Check if the amount exceeds the pending amount
            if (amount > pendingAmount) {
                this.$swal.showErrorToast(
                    `The amount may not be greater than ${pendingAmount}.`
                );
                return false;
            }

            // If all checks pass, return true
            return true;
        },
        resetFields() {
            this.errors = [];
            this.bills = [];
            this.form.bill_id = "";
            this.form.payment_type_id = "";
            this.form.receiver_id = "";
            this.form.discount = 0;
            this.form.amount = 0;
            this.form.remark = "";
            this.form.sale_date = new Date();
            this.form.date = new Date();
        },
        updateAmount() {
            this.form.amount = parseFloat(
                (this.pending_amount - this.form.discount).toFixed(2)
            );
        },
        updateSaleDate() {
            if (this.form.bill_id) {
                this.form.sale_date = this.form.bill_id.sale_date;
            }
        },
    },
    computed: {
        totalAmount() {
            let total = 0;
            if (this.form.bill_id) {
                total = this.form.bill_id.grand_total;
            }

            this.total_amount = parseFloat(total).toFixed(2);
            return this.total_amount;
        },
        pendingAmount() {
            let total = 0;
            let received_amount = 0;
            let pending_amount = 0;
            if (this.form.bill_id) {
                total = this.form.bill_id.grand_total;
                received_amount = this.form.bill_id.received_amount;
                pending_amount = total - received_amount;
            }

            this.pending_amount = parseFloat(pending_amount).toFixed(2);
            this.form.amount = parseFloat(pending_amount).toFixed(2);
            return this.pending_amount;
        },
    },
    watch: {
        "form.party_id"() {
            this.getBills();
        },
        "form.discount"() {
            this.updateAmount();
        },
        "form.bill_id"() {
            this.updateSaleDate();
            this.getPayments();
        },
    },
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
