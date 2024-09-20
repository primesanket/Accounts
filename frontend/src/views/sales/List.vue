<template>
    <div class="intro-y flex justify-between flex-col sm:flex-row items-center">
        <div class="flex mt-4">
            <div class="search hidden sm:block mr-auto">
                <input
                    type="text"
                    class="search__input form-control border-transparent"
                    placeholder="Search Sale..."
                    @input="handleSearch($event.target.value)"
                />
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    icon-name="search"
                    data-lucide="search"
                    class="lucide lucide-search search__icon dark:text-slate-500"
                >
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <div class="ml-5">
                <Datepicker
                    v-model="date"
                    format="MM-yyyy"
                    value-format="MM-yyyy"
                    auto-apply
                    placeholder="Pick a sale month"
                    class="p-0 border-0"
                    month-picker
                />
            </div>
            <div class="ml-5">
                <multiselect
                    track-by="text"
                    label="text"
                    v-model="bill_type_id"
                    :options="bill_types"
                    placeholder="Select Bill Type"
                    style="width: 250px"
                ></multiselect>
            </div>
            <div class="ml-5" v-if="selectedRows.length">
                <button
                    class="btn outline-none text-dark"
                    @click="downloadMultiPDF"
                    :disabled="multi_pdf_loader"
                >
                    <span v-if="multi_pdf_loader">
                        <svg
                            width="25"
                            viewBox="-2 -2 42 42"
                            xmlns="http://www.w3.org/2000/svg"
                            stroke="rgb(30, 41, 59)"
                            class="w-4 h-4 mr-2"
                        >
                            <g fill="none" fill-rule="evenodd">
                                <g transform="translate(1 1)" stroke-width="4">
                                    <circle
                                        stroke-opacity=".5"
                                        cx="18"
                                        cy="18"
                                        r="18"
                                    ></circle>
                                    <path d="M36 18c0-9.94-8.06-18-18-18">
                                        <animateTransform
                                            attributeName="transform"
                                            type="rotate"
                                            from="0 18 18"
                                            to="360 18 18"
                                            dur="1s"
                                            repeatCount="indefinite"
                                        ></animateTransform>
                                    </path>
                                </g>
                            </g>
                        </svg>
                    </span>
                    <span class="flex align-center gap-2"
                        ><FileDown size="20" class="" /> Download</span
                    >
                </button>
            </div>
        </div>
        <div class="flex mt-4">
            <router-link
                to="/sales/add-sale"
                class="btn btn-primary shadow-md mr-2"
                >Add Sale</router-link
            >
        </div>
    </div>
    <div class="divider mt-5 mx-1"></div>
    <DataTable
        ref="dataTable"
        :tableData="data.data"
        :currentPage="data.current_page"
        :totalPages="data.last_page"
        :columns="columns"
        :total="data.total"
        :from="data.from"
        :to="data.to"
        :loading="isLoading"
        :selectable="true"
        @search="handleSearch"
        @page-change="handlePageChange"
        @selection-change="handleSelectionChange"
        v-slot:action="{ row }"
    >
        <span class="flex">
            <button
                @click="downloadPDF(row)"
                class="btn btn-sm outline-none text-dark mr-2"
                :disabled="row.pdf_loader"
            >
                <span v-if="row.pdf_loader">
                    <svg
                        width="25"
                        viewBox="-2 -2 42 42"
                        xmlns="http://www.w3.org/2000/svg"
                        stroke="rgb(30, 41, 59)"
                        class="w-4 h-4 mr-2"
                    >
                        <g fill="none" fill-rule="evenodd">
                            <g transform="translate(1 1)" stroke-width="4">
                                <circle
                                    stroke-opacity=".5"
                                    cx="18"
                                    cy="18"
                                    r="18"
                                ></circle>
                                <path d="M36 18c0-9.94-8.06-18-18-18">
                                    <animateTransform
                                        attributeName="transform"
                                        type="rotate"
                                        from="0 18 18"
                                        to="360 18 18"
                                        dur="1s"
                                        repeatCount="indefinite"
                                    ></animateTransform>
                                </path>
                            </g>
                        </g>
                    </svg>
                </span>
                <span><FileDown size="16" class="" /></span>
            </button>
            <button
                @click="editAction(row)"
                class="btn btn-sm outline-none text-primary mr-2"
            >
                <FilePenLine size="16" class="mr-1" /> Edit
            </button>
            <button
                @click="deleteAction(row)"
                class="btn btn-sm outline-none text-danger"
            >
                <Trash2 size="16" class="mr-1" /> Delete
            </button>
        </span>
    </DataTable>
</template>

<script>
import apiClient from "@/services/axios";
import DataTable from "@/components/ui/DataTable.vue";
import { Trash2, FilePenLine, FileDown } from "lucide-vue-next";
import debounce from "lodash/debounce";
import { format } from "date-fns";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import Multiselect from "vue-multiselect";

export default {
    components: {
        DataTable,
        Trash2,
        FilePenLine,
        FileDown,
        Datepicker,
        Multiselect,
    },
    data() {
        return {
            columns: [
                { label: "Bill No.", key: "bill_no", class: "" },
                { label: "Sale Date", key: "sale_date", class: "" },
                { label: "Firm", key: "firm", class: "" },
                { label: "Bill Type", key: "bill_type", class: "" },
                { label: "Party", key: "party", class: "" },
                { label: "Total", key: "total", class: "" },
                { label: "CGST", key: "cgst", class: "" },
                { label: "SGST", key: "sgst", class: "" },
                { label: "IGST", key: "igst", class: "" },
                { label: "Grand Total", key: "grand_total", class: "" },
                { label: "Action", key: "action", class: "text-center w-20" },
            ],
            data: [],
            selectedRows: [],
            isLoading: false,
            multi_pdf_loader: false,
            search: "",
            date: [],
            bill_types: [],
            bill_type_id: "",
            current_page: 1,
        };
    },
    methods: {
        async fetchBootData() {
            try {
                const [bill_types] = await Promise.all([
                    apiClient.get(`/drop-down/bill_types`),
                ]);
                this.bill_types = bill_types.data;
            } catch (error) {
                console.error(error);
            } finally {
            }
        },
        async fetchData(page = 1, search = "") {
            const { month, year } = this.date || {};
            const date = month != null ? { month: (month ?? 0) + 1, year } : {};
            const { id: bill_type_id } = this.bill_type_id || {};

            try {
                this.isLoading = true;
                const response = await apiClient.get("/sale", {
                    params: { page, search, date, bill_type_id },
                });

                this.data = {
                    ...response.data,
                    data: response.data.data.map((item) => ({
                        ...item,
                        sale_date: this.formatDate(item.sale_date),
                    })),
                };
            } catch (error) {
                console.error("Fetch data error:", error);
            } finally {
                this.isLoading = false;
            }
        },
        // Debounce the search input
        debouncedSearch: debounce(function (search) {
            this.fetchData(this.current_page, search);
        }, 300),
        handleSearch(search) {
            this.search = search;
            this.debouncedSearch(search);
        },
        handlePageChange(page) {
            this.fetchData(page, this.search);
        },
        handleSelectionChange(selectedRows) {
            this.selectedRows = selectedRows;
        },
        async downloadMultiPDF() {
            this.multi_pdf_loader = true;

            const { month, year } = this.date || {};
            const date = month != null ? { month: (month ?? 0) + 1, year } : {};

            try {
                const response = await apiClient.post(
                    "/multi-invoice/pdf",
                    {
                        ids: this.selectedRows.map((item) => item.id),
                    },
                    {
                        responseType: "blob",
                    }
                );

                // Create a new Blob object using the response data
                const blob = new Blob([response.data], {
                    type: "application/zip",
                });

                // Create a link element
                const link = document.createElement("a");

                // Create a URL for the Blob and set it as the link's href
                const url = window.URL.createObjectURL(blob);
                link.href = url;

                // Set the download attribute with a file name
                const downloadFileName = date.month
                    ? `${date.month}-${date.year}-invoices.zip`
                    : "invoices.zip";

                link.setAttribute("download", downloadFileName);

                // Append the link to the body
                document.body.appendChild(link);

                // Trigger the download by simulating a click
                link.click();

                // Clean up
                window.URL.revokeObjectURL(url);
                document.body.removeChild(link);
            } catch (error) {
                console.error("Error downloading PDF:", error);
            } finally {
                this.multi_pdf_loader = false;
                this.$refs.dataTable.clearSelection();
            }
        },
        async downloadPDF(row) {
            row.pdf_loader = true;
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
            } finally {
                row.pdf_loader = false;
            }
        },
        editAction(row) {
            this.$router.push(`/sales/edit-sale/${row.id}`);
        },
        async deleteAction(row) {
            const confirmed = await this.$swal.showConfirm(
                "Are you sure?",
                `Are you sure you want to delete ${row.bill_no}?`
            );
            if (confirmed) {
                try {
                    const response = await apiClient.delete(`/sale/${row.id}`);
                    this.$swal.showSuccessToast("Deleted successfully!");
                    this.fetchData(this.current_page, this.search); // Refresh the data
                } catch (error) {
                    console.error("Delete sale error:", error);
                }
            } else {
                this.$swal.showErrorToast("Deletion has been canceled.");
            }
        },
        formatDate(date) {
            return format(new Date(date), "dd-MM-Y");
        },
    },
    mounted() {
        this.fetchBootData();
        this.fetchData();
    },
    watch: {
        date() {
            this.$refs.dataTable.clearSelection();
            this.fetchData();
        },
        bill_type_id() {
            this.$refs.dataTable.clearSelection();
            this.fetchData();
        },
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
