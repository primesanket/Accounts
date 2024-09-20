<template>
    <div class="intro-y flex flex-col sm:flex-row items-center">
        <div class="search hidden sm:block mr-auto">
            <input
                type="text"
                class="search__input form-control border-transparent"
                placeholder="Search Expense..."
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
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <router-link
                to="/expense/add-expense"
                class="btn btn-primary shadow-md mr-2"
                >Add Expense</router-link
            >
        </div>
    </div>
    <div class="divider mt-5 mx-1"></div>
    <DataTable
        :tableData="data.data"
        :currentPage="data.current_page"
        :totalPages="data.last_page"
        :columns="columns"
        :total="data.total"
        :from="data.from"
        :to="data.to"
        :loading="isLoading"
        @search="handleSearch"
        @page-change="handlePageChange"
        v-slot:action="{ row }"
    >
        <span class="flex">
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
import { Trash2, FilePenLine } from "lucide-vue-next";
import debounce from "lodash/debounce";
import { format } from "date-fns";

export default {
    components: {
        DataTable,
        Trash2,
        FilePenLine,
    },
    data() {
        return {
            columns: [
                { label: "Date", key: "expense_date", class: "" },
                { label: "Amount", key: "amount", class: "" },
                { label: "Type", key: "expense_type", class: "" },
                { label: "Description", key: "description", class: "" },
                { label: "Account By", key: "expense_account", class: "" },
                { label: "Action", key: "action", class: "text-center w-20" },
            ],
            data: [],
            isLoading: false,
            search: "",
            current_page: 1,
        };
    },
    methods: {
        async fetchData(page = 1, search = "") {
            try {
                this.isLoading = true;
                const response = await apiClient.get("/expense", {
                    params: {
                        page: page,
                        search: search,
                    },
                });

                this.data = {
                    ...response.data,
                    data: response.data.data.map((item) => ({
                        ...item,
                        expense_date: this.formatDate(item.expense_date),
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
        editAction(row) {
            this.$router.push(`/expense/edit-expense/${row.id}`);
        },
        async deleteAction(row) {
            const confirmed = await this.$swal.showConfirm(
                "Are you sure?",
                `Are you sure you want to delete ${row.product_name}?`
            );
            if (confirmed) {
                try {
                    const response = await apiClient.delete(
                        `/expense/${row.id}`
                    );
                    this.$swal.showSuccessToast("Deleted successfully!");
                    this.fetchData(this.current_page, this.search); // Refresh the data
                } catch (error) {
                    console.error("Delete expense error:", error);
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
        this.fetchData();
    },
};
</script>
