<template>
    <div class="overflow-x-auto lg:overflow-visible">
        <table class="table table-report">
            <thead>
                <tr>
                    <th v-if="selectable">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            @change="toggleSelectAll"
                            :checked="areAllSelected"
                        />
                    </th>
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
            <tbody v-if="loading">
                <tr>
                    <td colspan="100" class="transparent text-center">
                        <img class="loader" src="@/assets/images/loader.svg" />
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr
                    v-if="tableData && tableData.length > 0"
                    v-for="(item, index) in tableData"
                    :key="index"
                >
                    <td v-if="selectable">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            v-model="selectedRows"
                            :value="item"
                        />
                    </td>
                    <td
                        v-for="column in columns"
                        :key="column.key"
                        :class="[
                            { 'table-report__action': column.key === 'action' },
                            column.class,
                        ]"
                    >
                        <span v-if="column.key !== 'action'">{{
                            item[column.key]
                        }}</span>
                        <slot v-else name="action" :row="item"></slot>
                    </td>
                </tr>
                <tr v-else>
                    <td colspan="100" class="text-center">
                        No matching records found
                    </td>
                </tr>
            </tbody>
        </table>
        <div
            class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center justify-between mt-3"
            v-if="totalPages > 1 && loading === false"
        >
            <div class="">
                Showing {{ from }} to {{ to }} of {{ total }} entries
            </div>
            <nav class="">
                <ul class="pagination">
                    <li class="page-item">
                        <button
                            class="page-link"
                            @click="prevPage"
                            :class="{ 'disabled-link': currentPage === 1 }"
                        >
                            <ChevronLeft size="18" />
                        </button>
                    </li>
                    <li
                        class="page-item"
                        v-if="showFirstPage"
                        @click="changePage(1)"
                        :class="{ active: currentPage === 1 }"
                    >
                        <button class="page-link">1</button>
                    </li>
                    <li class="page-item" v-if="showEllipsisBefore">
                        <button class="page-link">...</button>
                    </li>
                    <li
                        class="page-item"
                        v-for="page in paginatedPages"
                        :key="page"
                        @click="changePage(page)"
                        :class="{ active: page === currentPage }"
                    >
                        <button class="page-link">{{ page }}</button>
                    </li>
                    <li class="page-item" v-if="showEllipsisAfter">
                        <button class="page-link">...</button>
                    </li>
                    <li
                        class="page-item"
                        v-if="showLastPage"
                        @click="changePage(totalPages)"
                        :class="{ active: currentPage === totalPages }"
                    >
                        <button class="page-link">{{ totalPages }}</button>
                    </li>
                    <li class="page-item">
                        <button
                            class="page-link"
                            @click="nextPage"
                            :class="{
                                'disabled-link': currentPage === totalPages,
                            }"
                        >
                            <ChevronRight size="18" />
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import { ChevronLeft, ChevronRight } from "lucide-vue-next";
export default {
    components: {
        ChevronLeft,
        ChevronRight,
    },
    props: {
        tableData: {
            type: Array,
            required: true,
        },
        columns: {
            type: Array,
            required: true,
            validator(value) {
                return value.every((col) => col.key && col.label);
            },
        },
        currentPage: {
            type: Number,
            required: true,
        },
        totalPages: {
            type: Number,
            required: true,
        },
        total: {
            type: Number,
            required: true,
        },
        from: {
            type: Number,
            required: true,
        },
        to: {
            type: Number,
            required: true,
        },
        loading: {
            type: Boolean,
            default: false,
        },
        selectable: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            selectedRows: [],
        };
    },
    computed: {
        paginatedPages() {
            const pages = [];
            let start = Math.max(this.currentPage - 2, 1);
            let end = Math.min(this.currentPage + 2, this.totalPages);

            if (this.totalPages <= 5) {
                start = 1;
                end = this.totalPages;
            } else {
                if (this.currentPage <= 3) {
                    end = 5;
                } else if (this.currentPage + 2 >= this.totalPages) {
                    start = this.totalPages - 4;
                }
            }

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },
        showFirstPage() {
            return this.totalPages > 5 && this.currentPage > 4;
        },
        showLastPage() {
            return (
                this.totalPages > 5 && this.currentPage < this.totalPages - 3
            );
        },
        showEllipsisBefore() {
            return this.showFirstPage && !this.paginatedPages.includes(2);
        },
        showEllipsisAfter() {
            return (
                this.showLastPage &&
                !this.paginatedPages.includes(this.totalPages - 1)
            );
        },
        areAllSelected() {
            return (
                this.tableData &&
                this.tableData.length > 0 &&
                this.selectedRows.length === this.tableData.length
            );
        },
    },
    methods: {
        changePage(page) {
            this.$emit("page-change", page);
        },
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.$emit("page-change", this.currentPage + 1);
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.$emit("page-change", this.currentPage - 1);
            }
        },
        toggleSelectAll() {
            if (this.areAllSelected) {
                this.selectedRows = [];
            } else {
                this.selectedRows = [...this.tableData];
            }
            this.$emit("selection-change", this.selectedRows);
        },
        clearSelection() {
            this.selectedRows = [];
        },
    },
    watch: {
        selectedRows(newVal) {
            this.$emit("selection-change", newVal);
        },
    },
};
</script>

<style scoped></style>
