<template>
    <!-- <PageTitle :isBackButtonAllow="false" class="mb-5" /> -->
    <div class="intro-y flex flex-col sm:flex-row items-center">
        <div class="search hidden sm:block mr-auto">
            <input
                type="text"
                class="search__input form-control border-transparent"
                placeholder="Search Party..."
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
                to="/masters/add-party"
                class="btn btn-primary shadow-md mr-2"
                >Add Party</router-link
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

export default {
    components: {
        DataTable,
        Trash2,
        FilePenLine,
    },
    data() {
        return {
            columns: [
                { label: "Party Name", key: "party_name", class: "" },
                { label: "Owner Name", key: "owner_name", class: "" },
                { label: "Reference Name", key: "reference_name", class: "" },
                { label: "Email", key: "email", class: "" },
                { label: "Mobile No", key: "mobile_no", class: "" },
                { label: "Office No", key: "office_no", class: "" },
                { label: "Pan No", key: "pan_no", class: "" },
                { label: "GSTTIN", key: "gst_tin", class: "" },
                { label: "Action", key: "action", class: "text-center" },
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
                const response = await apiClient.get("/party", {
                    params: {
                        page: page,
                        search: search,
                    },
                });
                this.isLoading = false;
                this.data = response.data;
            } catch (error) {
                this.isLoading = false;
                console.error("Fetch party data error:", error);
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
            this.$router.push(`/masters/edit-party/${row.id}`);
        },
        async deleteAction(row) {
            const confirmed = await this.$swal.showConfirm(
                "Are you sure?",
                `Are you sure you want to delete ${row.party_name}?`
            );
            if (confirmed) {
                try {
                    const response = await apiClient.delete(`/party/${row.id}`);
                    this.$swal.showSuccessToast("Deleted successfully!");
                    this.fetchData(this.current_page, this.search); // Refresh the data
                } catch (error) {
                    console.error("Delete party error:", error);
                }
            } else {
                this.$swal.showErrorToast("Deletion has been canceled.");
            }
        },
    },
    mounted() {
        this.fetchData();
    },
};
</script>
