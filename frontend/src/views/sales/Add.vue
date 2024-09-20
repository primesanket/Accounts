<template>
    <PageTitle :isBackButtonAllow="true" class="mb-5" />
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
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-3">
                                <label for="type" class="form-label">
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
                            <div class="col-span-3">
                                <label for="expense_date" class="form-label">
                                    Sale Date <span class="text-danger">*</span>
                                </label>

                                <Datepicker
                                    type="date"
                                    v-model="form.sale_date"
                                    format="dd-MM-yyyy"
                                    value-format="dd-MM-yyyy"
                                    auto-apply
                                    placeholder="Pick a date"
                                    class="p-0 border-0"
                                />

                                <div
                                    class="text-danger mt-2"
                                    v-if="errors.sale_date"
                                >
                                    {{ errors.sale_date[0] }}
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label for="account_by" class="form-label">
                                    Bill Type <span class="text-danger">*</span>
                                </label>
                                <multiselect
                                    track-by="text"
                                    label="text"
                                    v-model="form.bill_type_id"
                                    :options="bill_types"
                                    placeholder="Select Type"
                                ></multiselect>
                                <div
                                    class="text-danger mt-2"
                                    v-if="errors.bill_type_id"
                                >
                                    {{ errors.bill_type_id[0] }}
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label for="bill_no" class="form-label">
                                    Bill No
                                </label>
                                <input
                                    id="bill_no"
                                    type="text"
                                    v-model="form.bill_no"
                                    class="form-control"
                                    placeholder=""
                                    disabled
                                />
                                <div
                                    class="text-danger mt-2"
                                    v-if="errors.bill_no"
                                >
                                    {{ errors.bill_no[0] }}
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label for="days" class="form-label">
                                    Days
                                </label>
                                <input
                                    id="days"
                                    type="number"
                                    v-model="form.days"
                                    class="form-control"
                                    placeholder=""
                                />
                                <div
                                    class="text-danger mt-2"
                                    v-if="errors.days"
                                >
                                    {{ errors.days[0] }}
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label for="due_date" class="form-label">
                                    Due Date
                                </label>
                                <Datepicker
                                    type="date"
                                    v-model="form.due_date"
                                    format="dd-MM-yyyy"
                                    value-format="dd-MM-yyyy"
                                    auto-apply
                                    placeholder="Pick a date"
                                    class="p-0 border-0"
                                    disabled
                                />
                                <div
                                    class="text-danger mt-2"
                                    v-if="errors.due_date"
                                >
                                    {{ errors.due_date[0] }}
                                </div>
                            </div>
                            <div class="col-span-3">
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
                            <div class="col-span-3">
                                <label for="location_id" class="form-label">
                                    Location <span class="text-danger">*</span>
                                </label>
                                <multiselect
                                    track-by="text"
                                    label="text"
                                    v-model="form.location_id"
                                    :options="locations"
                                    placeholder="Select Location"
                                ></multiselect>
                                <div
                                    class="text-danger mt-2"
                                    v-if="errors.location_id"
                                >
                                    {{ errors.location_id[0] }}
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label for="currency_id" class="form-label">
                                    Currency <span class="text-danger">*</span>
                                </label>
                                <multiselect
                                    track-by="text"
                                    label="text"
                                    v-model="form.currency_id"
                                    :options="currencies"
                                    placeholder="Select Currency"
                                ></multiselect>
                                <div
                                    class="text-danger mt-2"
                                    v-if="errors.currency_id"
                                >
                                    {{ errors.currency_id[0] }}
                                </div>
                            </div>
                            <div class="col-span-3">
                                <label for="ex_rate" class="form-label">
                                    Ex. Rate
                                </label>
                                <input
                                    id="ex_rate"
                                    type="number"
                                    v-model="form.ex_rate"
                                    class="form-control"
                                    placeholder=""
                                    step="any"
                                />
                                <div
                                    class="text-danger mt-2"
                                    v-if="errors.ex_rate"
                                >
                                    {{ errors.ex_rate[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box p-5 mt-5">
                        <h2 class="text-md text-primary font-medium truncate">
                            Sale Detail
                        </h2>
                        <div class="border p-5 mt-3">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12">
                                    <label ref="product_id" class="form-label">
                                        Product
                                        <span class="text-danger">*</span>
                                    </label>
                                    <multiselect
                                        track-by="product_name"
                                        label="product_name"
                                        v-model="form_sale_detail.product_id"
                                        :options="products"
                                        placeholder="Select Product"
                                    ></multiselect>
                                    <div
                                        class="text-danger mt-2"
                                        v-if="
                                            form_sale_detail_errors.product_id
                                        "
                                    >
                                        {{
                                            form_sale_detail_errors
                                                .product_id[0]
                                        }}
                                    </div>
                                </div>
                                <div class="col-span-12">
                                    <label ref="description" class="form-label">
                                        Description
                                    </label>
                                    <textarea
                                        id="description"
                                        v-model="form_sale_detail.description"
                                        class="form-control"
                                        placeholder=""
                                        rows="3"
                                    />
                                    <div class="text-danger mt-2"></div>
                                </div>
                                <div class="col-span-3">
                                    <label ref="quantity" class="form-label">
                                        Quantity
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        id="quantity"
                                        type="number"
                                        v-model="form_sale_detail.quantity"
                                        class="form-control"
                                        placeholder=""
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="form_sale_detail_errors.quantity"
                                    >
                                        {{
                                            form_sale_detail_errors.quantity[0]
                                        }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label ref="rate" class="form-label">
                                        Rate
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        id="rate"
                                        type="number"
                                        v-model="form_sale_detail.rate"
                                        class="form-control"
                                        placeholder=""
                                        step="any"
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="form_sale_detail_errors.rate"
                                    >
                                        {{ form_sale_detail_errors.rate[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label ref="dollar" class="form-label">
                                        Dollar
                                    </label>
                                    <input
                                        id="dollar"
                                        type="number"
                                        v-model="form_sale_detail.dollar"
                                        class="form-control"
                                        placeholder=""
                                        disabled
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="form_sale_detail_errors.dollar"
                                    >
                                        {{ form_sale_detail_errors.dollar[0] }}
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <label ref="amount" class="form-label">
                                        Amount
                                    </label>
                                    <input
                                        id="amount"
                                        type="number"
                                        v-model="form_sale_detail.amount"
                                        class="form-control"
                                        placeholder=""
                                        disabled
                                    />
                                    <div
                                        class="text-danger mt-2"
                                        v-if="form_sale_detail_errors.amount"
                                    >
                                        {{ form_sale_detail_errors.amount[0] }}
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12">
                                    <div class="divider mt-8 mx-1"></div>
                                    <div class="flex justify-center">
                                        <button
                                            type="button"
                                            @click="addSaleDetail()"
                                            class="btn btn-sm btn-primary mt-3"
                                        >
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto lg:overflow-visible mt-5">
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
                                        v-if="
                                            form.sale_details &&
                                            form.sale_details.length > 0
                                        "
                                        v-for="(
                                            item, index
                                        ) in form.sale_details"
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
                                                v-if="column.key === 'action'"
                                            >
                                                <span class="flex">
                                                    <button
                                                        type="button"
                                                        @click="
                                                            removeSaleDetail(
                                                                index
                                                            )
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
                    <div class="intro-y mt-5 grid grid-cols-12">
                        <div class="col-span-9"></div>
                        <div class="col-span-3">
                            <div class="overflow-x-auto">
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <td>TOTAL</td>
                                            <td class="text-right">
                                                {{ totalAmount }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CGST</td>
                                            <td class="text-right">
                                                {{ totalCgst }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SGST</td>
                                            <td class="text-right">
                                                {{ totalSgst }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>IGST</td>
                                            <td class="text-right">
                                                {{ totalIgst }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>INVOICE TOTAL</td>
                                            <td class="text-right">
                                                {{ grandTotal }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="intro-y text-center">
                <LoadingButton
                    v-if="!isDataLoading"
                    :loading="isLoading"
                    class="mt-5"
                    >Submit</LoadingButton
                >
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
                sale_date: "",
                bill_type_id: "",
                bill_no: "",
                days: 0,
                due_date: "",
                party_id: "",
                location_id: { id: "GUJARAT", text: "Gujarat" },
                ex_rate: 0,
                currency_id: { id: "1", text: "INR" },
                sale_details: [],
                total: 0,
                cgst: 0,
                sgst: 0,
                igst: 0,
                grand_total: 0,
            },
            form_sale_detail: {
                product_id: "",
                description: "",
                quantity: 0,
                rate: 0,
                dollar: 0,
                amount: 0,
                cgst: 0,
                sgst: 0,
                igst: 0,
            },
            columns: [
                { label: "Sr.", key: "sr", class: "whitespace-nowrap w-10" },
                {
                    label: "Product Name",
                    key: "product_name",
                    class: "whitespace-nowrap w-40",
                },
                { label: "Description", key: "description", class: "" },
                {
                    label: "Qty",
                    key: "quantity",
                    class: "whitespace-nowrap w-20",
                },
                { label: "Rate", key: "rate", class: "whitespace-nowrap w-20" },
                {
                    label: "Amount",
                    key: "amount",
                    class: "whitespace-nowrap w-20",
                },
                {
                    label: "CGST",
                    key: "cgst",
                    class: "whitespace-nowrap w-20",
                },
                {
                    label: "SGST",
                    key: "sgst",
                    class: "whitespace-nowrap w-20",
                },
                {
                    label: "IGST",
                    key: "igst",
                    class: "whitespace-nowrap w-20",
                },
                { label: "Action", key: "action", class: "text-center w-20" },
            ],
            firms: [],
            bill_types: [],
            parties: [],
            locations: [
                { id: "GUJARAT", text: "Gujarat" },
                { id: "OUT_OF_GUJARAT", text: "Out Of Gujarat" },
            ],
            currencies: [],
            products: [],
            errors: [],
            form_sale_detail_errors: [],
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
                const [firms, bill_types, parties, currencies, products] =
                    await Promise.all([
                        apiClient.get(`/drop-down/firms`),
                        apiClient.get(`/drop-down/bill_types`),
                        apiClient.get(`/drop-down/parties`),
                        apiClient.get(`/drop-down/currencies`),
                        apiClient.get(`/drop-down/products`),
                    ]);
                this.firms = firms.data;
                this.bill_types = bill_types.data;
                this.parties = parties.data;
                this.currencies = currencies.data;
                this.products = products.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.isDataLoading = false;
            }
        },
        async handleSubmit() {
            this.isLoading = true;
            try {
                // Format sale_date and due_date if available
                const formattedSaleDate = this.form.sale_date
                    ? this.formatDate(new Date(this.form.sale_date))
                    : null;
                const formattedDueDate = this.form.due_date
                    ? this.formatDate(new Date(this.form.due_date))
                    : null;

                // Extract and format IDs from the form
                const {
                    firm_id,
                    bill_type_id,
                    party_id,
                    location_id,
                    currency_id,
                } = this.form;

                const saleDetailFormData = this.form.sale_details.map(
                    (product) => ({
                        ...product,
                        product_id: product.product_id?.id || null,
                    })
                );

                // Prepare form data to send
                const formData = {
                    ...this.form,
                    sale_date: formattedSaleDate,
                    due_date: formattedDueDate,
                    firm_id: firm_id?.id || null,
                    bill_type_id: bill_type_id?.id || null,
                    party_id: party_id?.id || null,
                    location_id: location_id?.id || null,
                    currency_id: currency_id?.id || null,
                    sale_details: saleDetailFormData,
                };

                // Send the POST request
                const response = await apiClient.post("/sale", formData);

                // Handle successful response
                if (response.status === 201) {
                    await this.downloadPDF(response.data.sale);
                    this.$swal.showSuccessToast(response.data.message);
                    setTimeout(() => {
                        this.isLoading = false;
                        this.$router.push("/sales");
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
        async downloadPDF(row) {
            try {
                const response = await apiClient.post(
                    `/invoice/pdf`,
                    {
                        id: row.id,
                    },
                    {
                        responseType: "blob",
                    }
                );

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
                link.setAttribute("download", row.bill_no + ".pdf");

                // Append the link to the body
                document.body.appendChild(link);

                // Trigger the download by simulating a click
                link.click();

                // Clean up
                window.URL.revokeObjectURL(url);
                document.body.removeChild(link);
            } catch (error) {
                console.error("Error downloading PDF:", error);
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
        // Method to validate sale detail form
        validateSaleDetail() {
            this.form_sale_detail_errors = {}; // Reset errors

            let isValid = true;
            if (!this.form_sale_detail.product_id) {
                this.form_sale_detail_errors.product_id = [
                    "The product field is required",
                ];
                isValid = false;
            }
            if (!this.form_sale_detail.quantity) {
                this.form_sale_detail_errors.quantity = [
                    "The quantity field is required",
                ];
                isValid = false;
            }
            if (!this.form_sale_detail.rate) {
                this.form_sale_detail_errors.rate = [
                    "The rate field is required",
                ];
                isValid = false;
            }

            return isValid;
        },
        // Method to add sale detail
        addSaleDetail() {
            if (this.validateSaleDetail()) {
                // Add the validated sale detail to the sale_details array
                this.form.sale_details.push({
                    ...this.form_sale_detail,
                    product_name: this.form_sale_detail.product_id.product_name,
                });

                // Reset the sale detail form for the next entry
                this.resetSaleDetailForm();
            } else {
                // If there are validation errors, scroll to the first error
                this.scrollToFirstErrorSaleDetailForm();
            }
        },
        removeSaleDetail(index) {
            this.form.sale_details.splice(index, 1);
        },
        // Method to reset sale detail form
        resetSaleDetailForm() {
            this.form_sale_detail = {
                product_id: "",
                description: "",
                quantity: 0,
                rate: 0,
                dollar: 0,
                amount: 0,
                cgst: 0,
                sgst: 0,
                igst: 0,
            };
        },
        // Method to scroll to the first error in the form
        scrollToFirstErrorSaleDetailForm() {
            // Use $nextTick to ensure the DOM is updated before querying
            this.$nextTick(() => {
                const firstErrorKey = Object.keys(
                    this.form_sale_detail_errors
                )[0];

                if (firstErrorKey) {
                    // Find the first error element by matching the "for" attribute to the error key
                    const firstErrorElement = this.$refs[firstErrorKey];

                    // If the error element exists, scroll to it
                    if (firstErrorElement && firstErrorElement.scrollIntoView) {
                        firstErrorElement.scrollIntoView({
                            behavior: "smooth",
                        });
                        firstErrorElement.focus(); // Optional: Focus the element for better accessibility
                    } else {
                        console.warn(
                            `No element found for error key: ${firstErrorKey}`
                        );
                    }
                } else {
                    console.warn("No errors found to scroll to.");
                }
            });
        },
        async createSaleBillNo(prefix = null, date = null) {
            try {
                const formData = {
                    prefix: prefix,
                    date: this.formatDate(date),
                };
                const response = await apiClient.post(
                    "/create-sale-invoice-id",
                    formData
                );
                if (response.status === 201) {
                    return response.data.invoiceId;
                }
            } catch (error) {
                if (error.response) {
                }
            }
        },
        async updateBillNo() {
            if (this.form.sale_date && this.form.bill_type_id) {
                const saleDate = new Date(this.form.sale_date);
                const billType = this.form.bill_type_id.alias;
                const billNo = await this.createSaleBillNo(billType, saleDate);
                if (billNo) {
                    this.form.bill_no = billNo;
                } else {
                    this.form.bill_no = "";
                }
            } else {
                this.form.bill_no = "";
            }
        },
        updateDueDate() {
            if (this.form.sale_date && !isNaN(this.form.days)) {
                const saleDate = new Date(this.form.sale_date);
                const days = parseInt(this.form.days);
                const dueDate = new Date(
                    saleDate.setDate(saleDate.getDate() + days)
                );

                // Format dueDate as required (e.g., dd-MM-yyyy)
                this.form.due_date = this.formatDate(dueDate);
            } else {
                this.form.due_date = ""; // Clear the due date if inputs are invalid
            }
        },
        updateCurrency() {
            if (
                this.form.bill_type_id &&
                this.form.bill_type_id.alias === "EX"
            ) {
                this.form.currency_id = { id: "2", text: "Dollar" };
            } else {
                this.form.currency_id = { id: "1", text: "INR" };
            }
        },
        updateDollarField() {
            if (
                this.form.currency_id &&
                this.form.currency_id.text === "Dollar"
            ) {
                this.form_sale_detail.dollar = this.form_sale_detail.rate;
            } else {
                this.form_sale_detail.dollar = 0;
            }
        },
        updateAmount() {
            const location = this.form.location_id.id || null;
            const bill_type = this.form.bill_type_id.alias || null;
            const gst = parseFloat(this.form_sale_detail.product_id.gst) || 18;
            const quantity = parseFloat(this.form_sale_detail.quantity) || 0;
            const rate = parseFloat(this.form_sale_detail.rate) || 0;
            const currency =
                this.form.currency_id && this.form.currency_id.text
                    ? this.form.currency_id.text
                    : "";
            const ex_rate = parseFloat(this.form.ex_rate) || 1;

            let amount;

            if (currency.toLowerCase() === "dollar") {
                amount = quantity * rate * ex_rate;
            } else {
                amount = quantity * rate;
            }

            let cgst_amount = 0;
            let sgst_amount = 0;
            let igst_amount = 0;

            if (gst) {
                if (bill_type == "TI") {
                    if (location == "GUJARAT") {
                        cgst_amount = (amount * (gst / 2)) / 100;
                        sgst_amount = (amount * (gst / 2)) / 100;
                    } else {
                        igst_amount = (amount * gst) / 100;
                    }
                }
            }

            this.form_sale_detail.cgst = cgst_amount;
            this.form_sale_detail.sgst = sgst_amount;
            this.form_sale_detail.igst = igst_amount;

            this.form_sale_detail.amount = parseFloat(amount.toFixed(4));
        },
        updateSaleDetails() {
            const location = this.form.location_id.id || null;
            const bill_type = this.form.bill_type_id.alias || null;

            this.form.sale_details = this.form.sale_details.map((detail) => {
                const gst = parseFloat(detail.product_id.gst) || 18;
                const quantity = parseFloat(detail.quantity) || 0;
                const rate = parseFloat(detail.rate) || 0;
                const currency = this.form.currency_id?.text || "";
                const ex_rate = parseFloat(this.form.ex_rate) || 1;

                let amount;

                if (currency.toLowerCase() === "dollar") {
                    amount = quantity * rate * ex_rate;
                } else {
                    amount = quantity * rate;
                }

                let cgst_amount = 0;
                let sgst_amount = 0;
                let igst_amount = 0;

                if (gst && bill_type === "TI") {
                    if (location === "GUJARAT") {
                        cgst_amount = (amount * (gst / 2)) / 100;
                        sgst_amount = (amount * (gst / 2)) / 100;
                    } else {
                        igst_amount = (amount * gst) / 100;
                    }
                }

                return {
                    ...detail,
                    amount: parseFloat(amount.toFixed(4)),
                    cgst: parseFloat(cgst_amount.toFixed(4)),
                    sgst: parseFloat(sgst_amount.toFixed(4)),
                    igst: parseFloat(igst_amount.toFixed(4)),
                };
            });
        },
    },
    computed: {
        totalAmount() {
            let total = 0;
            if (this.form.sale_details && this.form.sale_details.length) {
                total = this.form.sale_details.reduce(
                    (sum, item) => sum + item.amount,
                    0
                );
            }
            this.form.total = total;
            return total;
        },
        totalCgst() {
            let totalCgst = 0;
            if (this.form.sale_details && this.form.sale_details.length) {
                totalCgst = this.form.sale_details.reduce(
                    (sum, item) => sum + item.cgst,
                    0
                );
            }
            this.form.cgst = totalCgst;
            return totalCgst;
        },
        totalSgst() {
            let totalSgst = 0;
            if (this.form.sale_details && this.form.sale_details.length) {
                totalSgst = this.form.sale_details.reduce(
                    (sum, item) => sum + item.sgst,
                    0
                );
            }
            this.form.sgst = totalSgst;
            return totalSgst;
        },
        totalIgst() {
            let totalIgst = 0;
            if (this.form.sale_details && this.form.sale_details.length) {
                totalIgst = this.form.sale_details.reduce(
                    (sum, item) => sum + item.igst,
                    0
                );
            }
            this.form.igst = totalIgst;
            return totalIgst;
        },
        grandTotal() {
            let grand_total =
                this.totalAmount +
                this.totalCgst +
                this.totalSgst +
                this.totalIgst;
            this.form.grand_total = grand_total;
            return grand_total;
        },
    },
    watch: {
        "form.currency_id"(newCurrency) {
            this.updateDollarField();
            this.updateAmount();
        },
        "form.ex_rate"(newExRate) {
            this.updateAmount();
            this.updateSaleDetails();
        },
        "form.location_id"(newLocation) {
            this.updateAmount();
            this.updateSaleDetails();
        },
        "form.bill_type_id"(newBillType) {
            this.updateCurrency();
            this.updateBillNo();
            this.updateAmount();
            this.updateSaleDetails();
        },
        "form.sale_date"(newSaleDate) {
            this.updateBillNo();
            this.updateDueDate();
        },
        "form.days"(newDays) {
            this.updateDueDate();
        },
        "form_sale_detail.quantity"(newQuantity) {
            this.updateAmount();
        },
        "form_sale_detail.rate"(newRate) {
            this.updateDollarField();
            this.updateAmount();
        },
    },
    mounted() {},
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
.head-bg {
    background-color: rgb(var(--color-slate-100) / var(--tw-bg-opacity));
}
</style>
